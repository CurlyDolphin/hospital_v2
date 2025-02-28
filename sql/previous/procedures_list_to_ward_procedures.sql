START TRANSACTION;

INSERT INTO public.ward_procedure (
    id,
    procedure_id,
    ward_id,
    sequence,
    created_at,
    updated_at
)
SELECT
    id + 1089,
    procedures_id + 201 as procedure_id,
    source_id::INTEGER + 200 as ward_id,
        queue as sequence,
    NOW() AS created_at,
    NOW() AS updated_at
FROM public.procedure_list
WHERE source_type = 'chambers';

COMMIT;
