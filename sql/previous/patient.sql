START TRANSACTION;

INSERT INTO public.patient (
    id,
    name,
    last_name,
    gender,
    card_number,
    birthday,
    is_identified,
    created_at,
    updated_at
)
SELECT
    id,
    name,
    'Doe' as last_name,
    null as gender,
    card_number,
    null as birthday,
    false as is_identified,
    NOW() AS created_at,
    NOW() AS updated_at
FROM public.patients;

COMMIT;
