START TRANSACTION;

DO $$
DECLARE
created_at_value TIMESTAMP := '1970-01-01'::timestamp;
    updated_at_value TIMESTAMP := NOW();
    migration_count INTEGER;
    first_patient_id INTEGER;
    first_ward_id INTEGER;
    max_hospitalization_id INTEGER;
BEGIN
    -- Log start of migration
    RAISE NOTICE 'Starting hospitalization migration at %', updated_at_value;

    -- Get the current maximum ID in the hospitalization table
SELECT COALESCE(MAX(id), 0)
INTO max_hospitalization_id
FROM public.hospitalization;
RAISE NOTICE 'Current max hospitalization ID: %', max_hospitalization_id;

    -- Get the patient_id value from the patient table (first record where is_migrated = true)
SELECT id
INTO first_patient_id
FROM public.patient
WHERE is_migrated = true
ORDER BY id
    LIMIT 1;
RAISE NOTICE 'Selected patient_id from patient table: %', first_patient_id;

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
        patients_id,
        chambers_id,
        row_number() OVER (ORDER BY id) AS rn
    FROM public.chambers_patients
)
INSERT INTO public.hospitalization (
        id,
        patient_id,
        ward_id,
        discharge_date,
        created_at,
        updated_at,
        is_migrated
    )
SELECT
    max_hospitalization_id + rn AS id,
    first_patient_id + patients_id - 2 AS patient_id,
    first_ward_id + chambers_id - 1 AS ward_id,
    NOW() AS discharge_date,
    created_at_value AS created_at,
    updated_at_value AS updated_at,
    true AS is_migrated
FROM rows_to_migrate;

-- Get count of migrated records
GET DIAGNOSTICS migration_count = ROW_COUNT;

-- Log migration statistics
RAISE NOTICE 'Migration completed successfully';
    RAISE NOTICE 'Records migrated: %', migration_count;
    RAISE NOTICE 'New max hospitalization ID: %', max_hospitalization_id + migration_count;
    RAISE NOTICE 'Migration timestamp: %', updated_at_value;
END $$;

COMMIT;
