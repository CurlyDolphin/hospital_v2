START TRANSACTION;

INSERT INTO public.procedure (
    id,
    name,
    description,
    created_at,
    updated_at
)
SELECT
    id + 201,
    title as name,
    description,
    NOW() AS created_at,
    NOW() AS updated_at
FROM public.procedures;

COMMIT;
