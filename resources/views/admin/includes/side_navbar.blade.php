<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span>
                        <img alt="image" width="48px" height="48px" class="img-circle"
                            src="{{ URL::to('img/avatar.jpg') }}" />
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear"> <span class="block m-t-xs"> <strong
                                    class="font-bold text-capitalize">{{ Auth::user()->role }}</strong>
                            </span> <span class="text-muted text-xs block text-capitalize">{{ Auth::user()->name }}<b
                                    class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        @can('user-settings.password.update')
                        <li><a href="{{ URL('user-settings') }}"><span class="fa fa-gear fa-spin"></span> User Settings</a></li>
                        @endcan
                        <li class="divider"></li>
                        <li><a href="{{ URL('logout') }}"><span class="fa fa-sign-out"></span> Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    SMS
                </div>
            </li>
            @can('dashboard')
            <li class="{{ isActiveRoute('dashboard') }}">
                <a href="{{ URL('dashboard') }}" data-root="dashboard"><i class="fa fa-th-large"></i> <span
                        class="nav-label">Dashboard</span></a>
            </li>
            @endcan
            @can('students.index')
            <li class="{{ isActiveRoute('students.*') }}">
                <a href="{{ route('students.index') }}"><i
                        class="fa fa-group"></i> <span class="nav-label"></span>Students</a>
            </li>
            @endcan
            @can('teacher.index')
            <li class="{{ isActiveRoute('teacher.*') }}">
                <a href="{{ route('teacher.index') }}"><i class="entypo-users"></i> <span
                        class="nav-label"></span>Teachers</a>
            </li>
            @endcan
            @can('employee.index')
            <li class="{{ isActiveRoute('employee.*') }}">
                <a href="{{ route('employee.index') }}"><i class="fa fa-user-circle-o"></i> <span
                        class="nav-label"></span>Employees</a>
            </li>
            @endcan
            @can('guardian.index')
            <li class="{{ isActiveRoute('guardian.*') }}">
                <a href="{{ route('guardian.index') }}"><i class="fa fa-user"></i> <span
                        class="nav-label"></span>Guardians</a>
            </li>
            @endcan
            @canany(['manage-classes.index', 'manage-sections.index'])
                <li class="{{ isActiveRoute(['manage-classes.*','manage-sections.*']) }}">
                    <a><i class="fa fa-sitemap"></i> <span class="nav-label"></span><span
                            class="fa arrow"></span>Class</a>
                    <ul class="nav nav-second-level collapse">
                        @can('manage-classes.index')
                        <li class="{{ isActiveRoute('manage-classes.*') }}" data-show="">
                            <a href="{{ route('manage-classes.index') }}">Manage Classes</a>
                        </li>
                        @endcan
                        @can('manage-sections.index')
                        <li class="{{ isActiveRoute('manage-sections.*') }}" data-show="">
                            <a href="{{ route('manage-sections.index') }}">Manage Sections</a>
                        </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
            @canany(['vendors.index', 'items.index', 'vouchers.index'])
                <li class="{{ isActiveRoute(['vendors.*', 'items.*', 'vouchers.*']) }}">
                    <a><i class="fa fa-cubes"></i> <span class="nav-label"></span><span
                            class="fa arrow"></span>Inventory</a>
                    <ul class="nav nav-second-level collapse">
                        @can('vendors.index')
                        <li class="{{ isActiveRoute('vendors.*') }}" data-show="">
                            <a href="{{ route('vendors.index') }}">Vendors</a>
                        </li>
                        @endcan
                        @can('items.index')
                        <li class="{{ isActiveRoute('items.*') }}" data-show="">
                            <a href="{{ route('items.index') }}">Items</a>
                        </li>
                        @endcan
                        @can('vouchers.index')
                        <li class="{{ isActiveRoute('vouchers.*') }}" data-show="">
                            <a href="{{ route('vouchers.index') }}">Vouchers</a>
                        </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
            @can('routines.index')
            <li class="{{ isActiveRoute('routines.*') }}">
                <a href="{{ route('routines.index') }}"><i class="entypo-target"></i> <span
                        class="nav-label"></span>Class Routine</a>
            </li>
            @endcan
            @canany(['student-attendance.make.post', 'teacher-attendance.make.post', 'employee-attendance.make.post'])
                <li class="{{ isActiveRoute(['student-attendance.*','teacher-attendance.*','employee-attendance.*']) }}">
                    <a><i class="fa fa-bar-chart"></i> <span class="nav-label"></span><span
                            class="fa arrow"></span>Daily Attendance</a>
                    <ul class="nav nav-second-level collapse">
                        @can('student-attendance.make.post')
                        <li class="{{ isActiveRoute('student-attendance.*') }}" data-show="">
                            <a href="{{ route('student-attendance.index') }}">Student Attendance</a>
                        </li>
                        @endcan
                        @can('teacher-attendance.make.post')
                        <li class="{{ isActiveRoute('teacher-attendance.*') }}" data-show="">
                            <a href="{{ route('teacher-attendance.index') }}">Teacher Attendance</a>
                        </li>
                        @endcan
                        @can('employee-attendance.make.post')
                        <li class="{{ isActiveRoute('employee-attendance.*') }}" data-show="">
                            <a href="{{ route('employee-attendance.index') }}">Employee Attendance</a>
                        </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
            @can('manage-subjects.index')
            <li class="{{ isActiveRoute('manage-subjects.*') }}">
                <a href="{{ route('manage-subjects.index') }}"><i class="entypo-docs"></i> <span
                        class="nav-label"></span>Subjects</a>
            </li>
            @endcan
            @canany(['exam.index', 'manage-result.index', 'student-migrations.index'])
                <li class="{{ isActiveRoute(['exam.*','manage-result.*','student-migrations.*']) }}">
                    <a><i class="fa fa-graduation-cap"></i> <span class="nav-label"></span><span
                            class="fa arrow"></span>Exam</a>
                    <ul class="nav nav-second-level collapse">
                        @can('exam.index')
                        <li class="{{ isActiveRoute('exam.*') }}" data-show="">
                            <a href="{{ route('exam.index') }}">Exam</a>
                        </li>
                        @endcan
                        @can('manage-result.index')
                        <li class="{{ isActiveRoute('manage-result.*') }}" data-show="">
                            <a href="{{ route('manage-result.index') }}">Manage Result</a>
                        </li>
                        @endcan
                        @can('student-migrations.create')
                            <li class="{{ isActiveRoute('student-migrations.*') }}">
                                <a href="{{ route('student-migrations.index') }}">
                                    Student Migrations
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
            @can('library.index')
            <li class="{{ isActiveRoute('library.*') }}">
                <a href="{{ route('library.index') }}"><i class="fa fa-book"></i> <span
                        class="nav-label"></span>Library</a>
            </li>
            @endcan
            @can('noticeboard.index')
            <li class="{{ isActiveRoute('noticeboard.*') }}">
                <a href="{{ route('noticeboard.index') }}"><i class="fa fa-clipboard"></i> <span
                        class="nav-label"></span>Noticeboard</a>
            </li>
            @endcan
            @canany(['fee.index', 'expense.index'])
                <li class="{{ isActiveRoute(['fee.*', 'expense.*']) }}">
                    <a><i class="entypo-suitcase"></i> <span class="nav-label"></span><span
                            class="fa arrow"></span>Accounting</a>
                    <ul class="nav nav-second-level collapse">
                        @can('fee.index')
                        <li class="{{ isActiveRoute('fee.*') }}" data-show="">
                            <a href="{{ route('fee.index') }}">Fee</a>
                        </li>
                        @endcan
                        @can('expense.index')
                        <li class="{{ isActiveRoute('expense.*') }}" data-show="">
                            <a href="{{ route('expense.index') }}">Expense</a>
                        </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
            @can('smsnotifications.index')
            <li class="{{ isActiveRoute('smsnotifications.*') }}">
                <a href="{{ route('smsnotifications.index') }}"><i class="fa fa-paper-plane"></i>
                    <span class="nav-label"></span>SMS Notifications</a>
            </li>
            @endcan
            @canany(['seatsreport', 'fee-collection-reports.index', 'exam-reports.index'])
                <li class="{{ isActiveRoute(['seatsreport', 'fee-collection-reports.*','exam-reports.*']) }}">
                    <a><i class="fa fa-file"></i> <span class="nav-label"></span><span
                            class="fa arrow"></span>Report</a>
                    <ul class="nav nav-second-level collapse">
                        @can('seatsreport')
                        <li class="{{ isActiveRoute('seatsreport') }}" data-show="">
                            <a href="{{ route('seatsreport') }}">Seats Report</a>
                        </li>
                        @endcan
                        @can('fee-collection-reports.index')
                        <li class="{{ isActiveRoute('fee-collection-reports.*') }}" data-show="">
                            <a href="{{ route('fee-collection-reports.index') }}">Fee Collection</a>
                        </li>
                        @endcan
                        @can('exam-reports.index')
                        <li class="{{ isActiveRoute('exam-reports.*') }}" data-show="">
                            <a href="{{ route('exam-reports.index') }}">Exam reports</a>
                        </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
            @canany(['notifications.send', 'notifications.log'])
                <li class="{{ isActiveRoute(['notifications.index', 'notifications.log']) }}">
                    <a><i class="fa fa-bell"></i> <span class="nav-label"></span><span
                            class="fa arrow"></span>Notifications</a>
                    <ul class="nav nav-second-level collapse">
                        @can('notifications.send')
                            <li class="{{ isActiveRoute('notifications.index') }}" data-show="">
                                <a href="{{ route('notifications.index') }}">Send Message</a>
                            </li>
                        @endcan
                    </ul>
                    <ul class="nav nav-second-level collapse">
                        @can('notifications.log')
                            <li class="{{ isActiveRoute('notifications.log') }}" data-show="">
                                <a href="{{ route('notifications.log') }}">Log</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
            @canany(['users.index', 'roles.index', 'system-setting.index', 'roles.index', 'system-setting.index', 'exam-grades.index', 'academic-sessions.index'])
                <li class="{{ isActiveRoute(['users.*', 'roles.*', 'system-setting.*', 'fee-scenario.*', 'exam-grades.*', 'academic-sessions.*']) }}">
                    <a><i class="fa fa-gear fa-spin"></i> <span class="nav-label"></span><span
                            class="fa arrow"></span>Administrative Tools</a>
                    <ul class="nav nav-second-level collapse">
                        @can('users.index')
                        <li class="{{ isActiveRoute('users.*') }}">
                            <a href="{{ route('users.index') }}">Users</a>
                        </li>
                        @endcan
                        @can('roles.index')
                        <li class="{{ isActiveRoute('roles.*') }}">
                            <a href="{{ route('roles.index') }}">Roles</a>
                        </li>
                        @endcan
                        @role('Developer')
                            <li class="{{ isActiveRoute('academic-sessions.*') }}">
                                <a href="{{ route('academic-sessions.index') }}">
                                    <span class="nav-label"></span>Academic Session</a>
                            </li>
                        @endrole
                        @can('fee-scenario.index')
                        <li class="{{ isActiveRoute('fee-scenario.*') }}">
                            <a href="{{ route('fee-scenario.index') }}">Fee Scenario</a>
                        </li>
                        @endcan
                        @can('exam-grades.index')
                        <li class="{{ isActiveRoute('exam-grades.*') }}">
                            <a href="{{ route('exam-grades.index') }}">Exam Grades</a>
                        </li>
                        @endcan
                        @can('system-setting.index')
                        <li class="{{ isActiveRoute('system-setting.*') }}">
                            <a href="{{ route('system-setting.index') }}">System Setting</a>
                        </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
        </ul>

    </div>
</nav>
