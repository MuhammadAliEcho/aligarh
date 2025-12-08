#!/bin/bash
# Batch Update Script for Remaining Blade Files
# Usage: bash update_blade_headings.sh

cd /Users/espark/Documents/hash/aligarh/resources/views/admin

# Update all remaining h2 headings with translation keys
# Pattern: sed -i 's/<h2>OldText<\/h2>/<h2>{{ __("modules.key") }}<\/h2>/g'

# Medium-Priority Files
sed -i 's/<h2>Exams<\/h2>/<h2>{{ __("modules.pages_exams_title") }}<\/h2>/g' exam.blade.php
sed -i 's/<h2>Fees<\/h2>/<h2>{{ __("modules.pages_fees_title") }}<\/h2>/g' fee.blade.php
sed -i 's/<h2>Subjects<\/h2>/<h2>{{ __("modules.pages_subjects_title") }}<\/h2>/g' subjects.blade.php
sed -i 's/<h2>Roles<\/h2>/<h2>{{ __("modules.pages_roles_title") }}<\/h2>/g' roles.blade.php
sed -i 's/<h2>Sections<\/h2>/<h2>{{ __("modules.pages_sections_title") }}<\/h2>/g' sections.blade.php
sed -i 's/<h2>Vouchers<\/h2>/<h2>{{ __("modules.pages_vouchers_title") }}<\/h2>/g' voucher.blade.php
sed -i 's/<h2>Expenses<\/h2>/<h2>{{ __("modules.pages_expenses_title") }}<\/h2>/g' expense.blade.php
sed -i 's/<h2>Library<\/h2>/<h2>{{ __("modules.pages_library_title") }}<\/h2>/g' library.blade.php
sed -i 's/<h2>Guardians<\/h2>/<h2>{{ __("modules.pages_guardians_title") }}<\/h2>/g' guardian.blade.php
sed -i 's/<h2>Routines<\/h2>/<h2>{{ __("modules.pages_routines_title") }}<\/h2>/g' routines.blade.php
sed -i 's/<h2>Notice Boards<\/h2>/<h2>{{ __("modules.pages_notice_board_title") }}<\/h2>/g' notice_board.blade.php

# Update breadcrumb Home links
sed -i 's/<li>Home<\/li>/<li>{{ __("common.home") }}<\/li>/g' *.blade.php

echo "✓ Blade files updated with translation keys"
