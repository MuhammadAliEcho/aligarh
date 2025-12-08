<?php

/**
 * Permission Dependency Configuration
 * 
 * Defines parent-child relationships between permissions.
 * When a parent permission is granted to a role, dependent permissions are automatically granted.
 * This ensures users have all necessary permissions for complete module access.
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
    'students.create' => [
        'students.index',    // Must be able to view list to create
        'students.show',     // Must be able to view details
    ],
    
    'students.edit' => [
        'students.index',    // Must be able to view list
        'students.show',     // Must be able to view details
    ],
    
    'students.delete' => [
        'students.index',    // Must be able to view list
        'students.show',     // Must be able to view details
    ],
    
    'students.import' => [
        'students.index',    // Must be able to view list
        'students.create',   // Must be able to create
    ],
    
    'students.bulk.action' => [
        'students.index',    // Must be able to view list
        'students.edit',     // May need to edit during bulk action
    ],
    
    'students.attendance' => [
        'students.index',    // Must be able to view student list
    ],
    
    'students.result' => [
        'students.index',    // Must be able to view student list
    ],
    
    'students.fee' => [
        'students.index',    // Must be able to view student list
    ],
    
    // ==================== USERS MODULE ====================
    'users.create' => [
        'users.index',       // Must be able to view list to create
    ],
    
    'users.edit' => [
        'users.index',       // Must be able to view list
    ],
    
    'users.delete' => [
        'users.index',       // Must be able to view list
    ],
    
    // ==================== ROLES MODULE ====================
    'roles.create' => [
        'roles.index',       // Must be able to view list to create
    ],
    
    'roles.edit' => [
        'roles.index',       // Must be able to view list
    ],
    
    'roles.delete' => [
        'roles.index',       // Must be able to view list
    ],
    
    // ==================== TEACHERS MODULE ====================
    'teachers.create' => [
        'teachers.index',    // Must be able to view list
    ],
    
    'teachers.edit' => [
        'teachers.index',    // Must be able to view list
    ],
    
    'teachers.delete' => [
        'teachers.index',    // Must be able to view list
    ],
    
    'routines.add' => [
        'routines.index',    // Must be able to view list
    ],
    
    'routines.edit' => [
        'routines.index',    // Must be able to view list
    ],
    
    'routines.delete' => [
        'routines.index',    // Must be able to view list
    ],
    
    // ==================== ACADEMICS MODULE ====================
    'subjects.add' => [
        'subjects.index',    // Must be able to view list
    ],
    
    'subjects.edit' => [
        'subjects.index',    // Must be able to view list
    ],
    
    'subjects.delete' => [
        'subjects.index',    // Must be able to view list
    ],
    
    'classes.add' => [
        'classes.index',     // Must be able to view list
    ],
    
    'classes.edit' => [
        'classes.index',     // Must be able to view list
    ],
    
    'classes.delete' => [
        'classes.index',     // Must be able to view list
    ],
    
    // ==================== FEES MODULE ====================
    'fees.add' => [
        'fees.index',        // Must be able to view list
    ],
    
    'fees.edit' => [
        'fees.index',        // Must be able to view list
    ],
    
    'fees.delete' => [
        'fees.index',        // Must be able to view list
    ],
    
    'invoice.add' => [
        'invoice.index',     // Must be able to view list
    ],
    
    'invoice.edit' => [
        'invoice.index',     // Must be able to view list
    ],
    
    // ==================== EXAMS MODULE ====================
    'exam.add' => [
        'exam.index',        // Must be able to view list
    ],
    
    'exam.edit' => [
        'exam.index',        // Must be able to view list
    ],
    
    'exam.delete' => [
        'exam.index',        // Must be able to view list
    ],
    
    'manage-result.make' => [
        'manage-result.index', // Must be able to view list
    ],
    
    // ==================== LIBRARY MODULE ====================
    'library.add' => [
        'library.index',     // Must be able to view list
    ],
    
    'library.edit' => [
        'library.index',     // Must be able to view list
    ],
    
    'library.delete' => [
        'library.index',     // Must be able to view list
    ],
    
    // ==================== ACCOUNTING MODULE ====================
    'vouchers.add' => [
        'vouchers.index',    // Must be able to view list
    ],
    
    'vouchers.edit' => [
        'vouchers.index',    // Must be able to view list
    ],
    
    'vouchers.delete' => [
        'vouchers.index',    // Must be able to view list
    ],
    
    'vendors.add' => [
        'vendors.index',     // Must be able to view list
    ],
    
    'vendors.edit' => [
        'vendors.index',     // Must be able to view list
    ],
    
    'vendors.delete' => [
        'vendors.index',     // Must be able to view list
    ],
    
    'items.add' => [
        'items.index',       // Must be able to view list
    ],
    
    'items.edit' => [
        'items.index',       // Must be able to view list
    ],
    
    'items.delete' => [
        'items.index',       // Must be able to view list
    ],
    
];
