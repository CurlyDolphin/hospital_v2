WITH max_queue_by_source AS (
    SELECT source_id,
           MAX(queue) AS max_queue
    FROM public.procedure_list
    WHERE source_type = 'chambers'
    GROUP BY source_id
),
duplicates AS (
    SELECT id, source_id, queue,
        ROW_NUMBER() OVER (PARTITION BY source_id, queue ORDER BY id) AS row_num
    FROM public.procedure_list
    WHERE source_type = 'chambers'
)
UPDATE public.procedure_list
SET queue = m.max_queue + d.row_num
    FROM max_queue_by_source m, duplicates d
WHERE procedure_list.id = d.id
  AND procedure_list.source_id = d.source_id
  AND procedure_list.source_id = m.source_id
  AND d.row_num > 1;