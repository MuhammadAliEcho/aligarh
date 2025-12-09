<?php

/**
 * Permission Dependency Configuration
 * 
 * Defines parent-child relationships between permissions.
 * When a parent permission is granted to a role, dependent permissions are automatically granted.
 * This ensures users have all necessary permissions for complete module access.
 * 
 * IMPORTANT: Only use permissions that exist in the form (from getPermissions() method)
 * Do NOT reference permissions that don't have routes or are in ignore_routes list
 * 
 * Structure:
 * 'parent_permission' => [
 *     'child_1',
 *     'child_2',
 *     ...
 * ]
 * 
 * When 'parent_permission' is granted, all children are automatically granted.
 * When 'parent_permission' is revoked, all children are automatically revoked.
 */

return [
    
    // ==================== STUDENTS MODULE ====================
    'students.add' => [
        'students.index',     // Must be able to view list to create
    ],
    
    'students.edit.post' => [
        'students.index',     // Must be able to view list to edit
    ],
    
    'students.grid' => [
        'students.index',     // Grid view is alternative display of index
    ],
    
    'students.profile' => [
        'students.index',     // Profile view needs list context
    ],
    
    // ==================== TEACHER MODULE ====================
    'teacher.add' => [
        'teacher.index',      // Must be able to view list to create
    ],
    
    'teacher.edit.post' => [
        'teacher.index',      // Must be able to view list to edit
    ],
    
    'teacher.grid' => [
        'teacher.index',      // Grid view is alternative display of index
    ],
    
    'teacher.profile' => [
        'teacher.index',      // Profile view needs list context
    ],
    
    // ==================== EMPLOYEE MODULE ====================
    'employee.add' => [
        'employee.index',     // Must be able to view list to create
    ],
    
    'employee.edit.post' => [
        'employee.index',     // Must be able to view list to edit
    ],
    
    'employee.grid' => [
        'employee.index',     // Grid view is alternative display of index
    ],
    
    'employee.profile' => [
        'employee.index',     // Profile view needs list context
    ],
    
    // ==================== GUARDIAN MODULE ====================
    'guardian.add' => [
        'guardian.index',     // Must be able to view list to create
    ],
    
    'guardian.edit.post' => [
        'guardian.index',     // Must be able to view list to edit
    ],
    
    'guardian.grid' => [
        'guardian.index',     // Grid view is alternative display of index
    ],
    
    'guardian.profile' => [
        'guardian.index',     // Profile view needs list context
    ],
    
    // ==================== VISITORS MODULE ====================
    'visitors.create' => [
        'visitors.index',     // Must be able to view list to create
    ],
    
    'visitors.update' => [
        'visitors.index',     // Must be able to view list to edit
    ],
    
    'visitors.grid' => [
        'visitors.index',     // Grid view is alternative display of index
    ],
    
    'visitors.profile' => [
        'visitors.index',     // Profile view needs list context
    ],
    
    'visitors.delete' => [
        'visitors.index',     // Must see list to delete
    ],
    
    // ==================== MANAGE CLASSES MODULE ====================
    'manage-classes.add' => [
        'manage-classes.index', // Must be able to view list to create
    ],
    
    'manage-classes.edit.post' => [
        'manage-classes.index', // Must be able to view list to edit
    ],
    
    // ==================== MANAGE SECTIONS MODULE ====================
    'manage-sections.add' => [
        'manage-sections.index', // Must be able to view list to create
    ],
    
    'manage-sections.edit.post' => [
        'manage-sections.index', // Must be able to view list to edit
    ],
    
    // ==================== VENDORS MODULE ====================
    'vendors.add' => [
        'vendors.index',      // Must be able to view list to create
    ],
    
    'vendors.edit.post' => [
        'vendors.index',      // Must be able to view list to edit
    ],
    
    // ==================== ITEMS MODULE ====================
    'items.add' => [
        'items.index',        // Must be able to view list to create
    ],
    
    'items.edit.post' => [
        'items.index',        // Must be able to view list to edit
    ],
    
    // ==================== VOUCHERS MODULE ====================
    'vouchers.add' => [
        'vouchers.index',     // Must be able to view list to create
    ],
    
    'vouchers.edit.post' => [
        'vouchers.index',     // Must be able to view list to edit
    ],
    
    'vouchers.detail' => [
        'vouchers.index',     // Must see list to view details
    ],
    
    // ==================== ROUTINES MODULE ====================
    'routines.add' => [
        'routines.index',     // Must be able to view list to create
    ],
    
    'routines.edit.post' => [
        'routines.index',     // Must be able to view list to edit
    ],
    
    'routines.delete' => [
        'routines.index',     // Must see list to delete
    ],
    
    // ==================== MANAGE SUBJECTS MODULE ====================
    'manage-subjects.add' => [
        'manage-subjects.index', // Must be able to view list to create
    ],
    
    'manage-subjects.edit.post' => [
        'manage-subjects.index', // Must be able to view list to edit
    ],
    
    // ==================== EXAM MODULE ====================
    'exam.add' => [
        'exam.index',         // Must be able to view list to create
    ],
    
    'exam.edit.post' => [
        'exam.index',         // Must be able to view list to edit
    ],
    
    // ==================== QUIZZES MODULE ====================
    'quizzes.create' => [
        'quizzes.index',      // Must be able to view list to create
    ],
    
    'quizzes.update' => [
        'quizzes.index',      // Must be able to view list to edit
    ],
    
    'quizzes.delete' => [
        'quizzes.index',      // Must see list to delete
    ],
    
    // ==================== LIBRARY MODULE ====================
    'library.add' => [
        'library.index',      // Must be able to view list to create
    ],
    
    'library.edit.post' => [
        'library.index',      // Must be able to view list to edit
    ],
    
    // ==================== FEE MODULE ====================
    'fee.create.store' => [
        'fee.index',          // Must be able to view fee dashboard to create
    ],
    
    'fee.collect.store' => [
        'fee.index',          // Must be able to view fee dashboard to collect
    ],
    
    'fee.edit.invoice.post' => [
        'fee.index',          // Must be able to view fee dashboard to edit invoice
    ],
    
    // ==================== EXPENSE MODULE ====================
    'expense.add' => [
        'expense.index',      // Must be able to view list to create
    ],
    
    'expense.edit.post' => [
        'expense.index',      // Must be able to view list to edit
    ],
    
    // ==================== USERS MODULE ====================
    'users.create' => [
        'users.index',        // Must be able to view list to create
    ],
    
    'users.update' => [
        'users.index',        // Must be able to view list to edit
    ],
    
    // ==================== ROLES MODULE ====================
    'roles.create' => [
        'roles.index',        // Must be able to view list to create
    ],
    
    'roles.update' => [
        'roles.index',        // Must be able to view list to edit
    ],
    
    // ==================== MANAGE RESULT MODULE ====================
    'manage-result.make' => [
        'manage-result.index', // Must see dashboard to make result
    ],
    
    'manage-result.maketranscript' => [
        'manage-result.index', // Must see dashboard to make transcript
    ],
    
    // ==================== ATTENDANCE LEAVE MODULE ====================
    'attendance-leave.make' => [
        'attendance-leave.index', // Must see leave list to add leave
    ],
    
    'attendance-leave.update' => [
        'attendance-leave.index', // Must see leave list to edit
    ],
    
    'attendance-leave.delete' => [
        'attendance-leave.index', // Must see leave list to delete
    ],
    
    // ==================== NOTICEBOARD MODULE ====================
    'noticeboard.create' => [
        'noticeboard.index',  // Must see notices to post new
    ],
    
    'noticeboard.delete' => [
        'noticeboard.index',  // Must see notices to delete
    ],
    
];
