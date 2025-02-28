START TRANSACTION;

WITH params AS (
    SELECT '1970-01-01'::timestamp AS start_date, 1000 AS step_limit
),
     max_val AS (
         SELECT COALESCE(MAX(id), 0) AS max_id FROM public.ward
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
    (SELECT max_id FROM max_val) + rn AS id,
    number,
    (SELECT start_date FROM params) AS created_at,
    NOW() AS updated_at,
    'Описание не задано' AS description,
    true AS is_migrated
FROM rows_to_migrate;

COMMIT;
