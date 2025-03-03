START TRANSACTION;

DO $$
DECLARE
updated_at_value TIMESTAMP := NOW();
    migration_count INTEGER;
    max_patient_id INTEGER;
BEGIN
    -- Log start of migration
    RAISE NOTICE 'Starting patient migration at %', updated_at_value;

    -- Get the current max ID
SELECT COALESCE(MAX(id), 0) INTO max_patient_id FROM public.patient;
RAISE NOTICE 'Current max patient ID: %', max_patient_id;

WITH params AS (
    SELECT '1970-01-01'::timestamp AS start_date, 10000 AS step_limit
),
     rows_to_migrate AS (
         SELECT
             row_number() OVER (ORDER BY id) AS rn,
                 name,
             card_number
         FROM public.patients
                  LIMIT (SELECT step_limit FROM params)
     )
INSERT INTO public.patient (
        id,
        name,
        last_name,
        gender,
        card_number,
        birthday,
        is_identified,
        created_at,
        updated_at,
        is_migrated
    )
SELECT
    max_patient_id + rn AS id,
    name,
    'Doe' AS last_name,
    NULL AS gender,
    card_number,
    NULL AS birthday,
    false AS is_identified,
    (SELECT start_date FROM params) AS created_at,
    updated_at_value AS updated_at,
    true AS is_migrated
FROM rows_to_migrate;

-- Get count of migrated records
GET DIAGNOSTICS migration_count = ROW_COUNT;

-- Log migration statistics
RAISE NOTICE 'Migration completed successfully';
    RAISE NOTICE 'Records migrated: %', migration_count;
    RAISE NOTICE 'New max patient ID: %', max_patient_id + migration_count;
    RAISE NOTICE 'Migration timestamp: %', updated_at_value;
END $$;

COMMIT;