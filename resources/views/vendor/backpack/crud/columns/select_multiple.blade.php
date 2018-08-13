{{-- relationships with pivot table (n-n) --}}
<span>
    <?php
        $results = $entry->{$column['entity']};

        if ($results && $results->count()) {
            $results_array = $results->pluck($column['attribute'])->toArray();
            foreach ($results_array as &$value) {
            	$value = ucwords(__($value));
            }
            echo implode(', ', $results_array);
        } else {
            echo '-';
        }
    ?>
</span>
