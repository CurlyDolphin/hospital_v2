START TRANSACTION;

DO $$
DECLARE
updated_at_value TIMESTAMP := NOW();
    p_step_limit INTEGER := 2000;
    migration_count INTEGER;
    max_ward_id INTEGER;
BEGIN
    -- Log start of migration
    RAISE NOTICE 'Starting ward migration at %', updated_at_value;
    RAISE NOTICE 'Step limit set to % records', p_step_limit;

    -- Log start of migration
SELECT COALESCE(MAX(id), 0) INTO max_ward_id FROM public.ward;
RAISE NOTICE 'Current max ward ID: %', max_ward_id;

WITH params AS (
    SELECT '1970-01-01'::timestamp AS start_date, p_step_limit AS step_limit
),
     rows_to_migrate AS (
         SELECT
             row_number() OVER (ORDER BY id) AS rn,
                 number
         FROM public.chambers
                  LIMIT (SELECT step_limit FROM params)
     )
INSERT INTO public.ward (
        id,
        ward_number,
        created_at,
        updated_at,
        description,
        is_migrated
    )
SELECT
    max_ward_id + rn AS id,
    number,
    (SELECT start_date FROM params) AS created_at,
    updated_at_value AS updated_at,
    'Описание не задано' AS description,
    true AS is_migrated
FROM rows_to_migrate;

-- Get count of migrated records
GET DIAGNOSTICS migration_count = ROW_COUNT;

-- Log migration statistics
RAISE NOTICE 'Ward migration completed successfully';
    RAISE NOTICE 'Wards migrated: %', migration_count;
    RAISE NOTICE 'New max ward ID: %', max_ward_id + migration_count;
    RAISE NOTICE 'Migration timestamp: %', updated_at_value;
END $$;

COMMIT;