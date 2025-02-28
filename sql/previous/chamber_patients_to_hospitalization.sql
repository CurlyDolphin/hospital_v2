START TRANSACTION;

INSERT INTO public.hospitalization (
    id,
    patient_id,
    ward_id,
    discharge_date,
    created_at,
    updated_at
)
SELECT
    id,
    patients_id as patient_id,
    chambers_id + 200 as ward_id,
    NOW() AS discharge_date,
    NOW() AS created_at,
    NOW() AS updated_at
FROM public.chambers_patients;

COMMIT;
