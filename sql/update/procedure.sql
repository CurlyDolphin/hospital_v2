START TRANSACTION;

DO $$
DECLARE
updated_at_value TIMESTAMP := NOW();
    migration_count INTEGER;
    max_procedure_id INTEGER;
    p_step_limit INTEGER := 2000;
BEGIN
    -- Log start of migration
    RAISE NOTICE 'Starting procedure migration at %', updated_at_value;
    RAISE NOTICE 'Step limit set to % records', p_step_limit;

    -- Get the current max ID
SELECT COALESCE(MAX(id), 0) INTO max_procedure_id FROM public.procedure;
RAISE NOTICE 'Current max procedure ID: %', max_procedure_id;

WITH params AS (
    SELECT '1970-01-01'::timestamp AS start_date, p_step_limit AS step_limit
),
     rows_to_migrate AS (
         SELECT
             row_number() OVER (ORDER BY id) AS rn,
                 title,
             description
         FROM public.procedures
                  LIMIT (SELECT step_limit FROM params)
     )
INSERT INTO public.procedure (
        id,
        name,
        description,
        created_at,
        updated_at,
        is_migrated
    )
SELECT
    max_procedure_id + rn AS id,
    title AS name,
    description,
    (SELECT start_date FROM params) AS created_at,
    updated_at_value AS updated_at,
    true AS is_migrated
FROM rows_to_migrate;

-- Get count of migrated records
GET DIAGNOSTICS migration_count = ROW_COUNT;

-- Log migration statistics
RAISE NOTICE 'Procedure migration completed successfully';
    RAISE NOTICE 'Procedures migrated: %', migration_count;
    RAISE NOTICE 'New max procedure ID: %', max_procedure_id + migration_count;
    RAISE NOTICE 'Migration timestamp: %', updated_at_value;
END $$;

COMMIT;