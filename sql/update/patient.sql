START TRANSACTION;

WITH params AS (
    SELECT '1970-01-01'::timestamp AS start_date, 10000 AS step_limit
),
     max_val AS (
         SELECT COALESCE(MAX(id), 0) AS max_id FROM public.patient
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
    (SELECT max_id FROM max_val) + rn AS id,
    name,
    'Doe' AS last_name,
    NULL AS gender,
    card_number,
    NULL AS birthday,
    false AS is_identified,
    (SELECT start_date FROM params) AS created_at,
    NOW() AS updated_at,
    true AS is_migrated
FROM rows_to_migrate;

COMMIT;
