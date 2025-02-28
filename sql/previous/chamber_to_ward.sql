START TRANSACTION;

INSERT INTO public.ward (
    id,
    ward_number,
    created_at,
    updated_at
)
SELECT
    id + 200 AS id,  -- Смещение ID на 200
    number + 1000 AS ward_number,
    NOW() AS created_at,
    NOW() AS updated_at
FROM public.chambers;

COMMIT;

UPDATE ward
SET description = 'Описание не задано'
WHERE description IS NULL;