START TRANSACTION;

DO $$
DECLARE
created_at_value TIMESTAMP := '1970-01-01'::timestamp;
    updated_at_value TIMESTAMP := NOW();
    migration_count INTEGER;
    first_procedure_id INTEGER;
    first_ward_id INTEGER;
    max_ward_procedure_id INTEGER;
BEGIN
    -- Log start of migration
    RAISE NOTICE 'Starting ward procedure migration at %', updated_at_value;

    -- Get the current maximum ID in the ward_procedure table
SELECT COALESCE(MAX(id), 0)
INTO max_ward_procedure_id
FROM public.ward_procedure;
RAISE NOTICE 'Current max ward_procedure ID: %', max_ward_procedure_id;

    -- Get the procedure_id value from the procedure table (first record where is_migrated = true)
SELECT id
INTO first_procedure_id
FROM public.procedure
WHERE is_migrated = true
ORDER BY id
    LIMIT 1;
RAISE NOTICE 'Selected procedure_id from procedure table: %', first_procedure_id;

    -- Get the ward_id value from the ward table (first record where is_migrated = true)
SELECT id
INTO first_ward_id
FROM public.ward
WHERE is_migrated = true
ORDER BY id
    LIMIT 1;
RAISE NOTICE 'Selected ward_id from ward table: %', first_ward_id;

WITH rows_to_migrate AS (
    SELECT
        id,
        procedures_id,
        source_id,
        queue,
        row_number() OVER (ORDER BY id) AS rn
    FROM public.procedure_list
    WHERE source_type = 'chambers'
)
INSERT INTO public.ward_procedure (
        id,
        procedure_id,
        ward_id,
        sequence,
        created_at,
        updated_at,
        is_migrated
    )
SELECT
    max_ward_procedure_id + rn AS id,
    first_procedure_id + procedures_id - 1 AS procedure_id,
    first_ward_id + source_id::INTEGER - 1 AS ward_id,
        queue AS sequence,
    created_at_value AS created_at,
    updated_at_value AS updated_at,
    true AS is_migrated
FROM rows_to_migrate;

-- Get count of migrated records
GET DIAGNOSTICS migration_count = ROW_COUNT;

-- Log migration statistics
RAISE NOTICE 'Migration completed successfully';
    RAISE NOTICE 'Records migrated: %', migration_count;
    RAISE NOTICE 'New max ward_procedure ID: %', max_ward_procedure_id + migration_count;
    RAISE NOTICE 'Migration timestamp: %', updated_at_value;
END $$;

COMMIT;
