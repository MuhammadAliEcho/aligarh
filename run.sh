#!/bin/bash

MIGRATION_DIR="database/migrations"
declare -A table_map

echo "Scanning for duplicate migration files..."

for file in "$MIGRATION_DIR"/*.php; do
    if grep -q "Schema::create('" "$file"; then
        # Extract table name from Schema::create('table_name')
        table=$(grep "Schema::create('" "$file" | sed -n "s/.*Schema::create('\([^']*\)'.*/\1/p")

        if [[ -n "$table" ]]; then
            if [[ -n "${table_map[$table]}" ]]; then
                echo "Deleting duplicate migration for table '$table': $file"
                rm "$file"
            else
                table_map["$table"]="$file"
            fi
        fi
    fi
done

echo "Done."
