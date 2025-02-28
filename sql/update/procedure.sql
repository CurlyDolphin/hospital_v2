START TRANSACTION;

WITH params AS (
    SELECT '1970-01-01'::timestamp AS start_date, 1000 AS step_limit  -- задаём дату и лимит строк
),
     max_val AS (
         SELECT COALESCE(MAX(id), 0) AS max_id FROM public.procedure
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
    (SELECT max_id FROM max_val) + rn AS id,
    title AS name,
    description,
    (SELECT start_date FROM params) AS created_at,
    NOW() AS updated_at,
    true AS is_migrated
FROM rows_to_migrate;

COMMIT;
