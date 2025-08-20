@extends('admin.layouts.master')

@section('title', 'Messsage Notifications |')

@section('head')
    <link href="{{ URL::to('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">

    <style>
        .label-student-token {
            background: linear-gradient(#0f73d1 0%, #1c5b97 100%);
        }

        .label-teacher-token {
            background: linear-gradient(#12d10f 0%, #14cb1191 100%);
        }

        .label-employee-token {
            background: linear-gradient(#ed5565 0%, #b75862 100%);
        }

        .label-guardian-token {
            background: linear-gradient(#f8ac59 0%, #f39229c7 100%);
        }
    </style>
@endsection

@section('content')

    @include('admin.includes.side_navbar')
    <div id="page-wrapper" class="gray-bg">
        @include('admin.includes.top_navbar')

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-md-6">
                <h2>Notifications</h2>
                <ol class="breadcrumb">
                    <li>Home</li>
                    <li class="active"><a>Send</a></li>
                </ol>
            </div>
            <div class="col-lg-4 col-md-6">
                @include('admin.includes.academic_session')
            </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h2>Messsage Send</h2>
                        </div>
                        <div class="ibox-content">
                            <form id="notification" method="post" action="{{ route('msg-notifications.send') }}"
                                class="form-horizontal">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="type">Type</label>
                                    <div class="col-sm-8">
                                        <select name="type" id="type" class="form-control" v-model="type"
                                            @change="handleTypeChange" required>
                                            <option value="">-- Select Type --</option>
                                            <option value="students">Student</option>
                                            <option value="guardians">Guardian</option>
                                            <option value="teachers">Teacher</option>
                                            <option value="employees">Employee</option>
                                        </select>
                                    </div>
                                </div>

                                <div v-if="type === 'students'" class="form-group">
                                    <label class="col-sm-2 control-label">Students</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" v-model="selectedId"
                                            @change="onSelectionChangeStudents" required>
                                            <option value="">-- Select Class or Student --</option>
                                            <optgroup v-for="(group, index) in studentsByClass" :key="index"
                                                :label="group.class_name">
                                                <option :value="'class_' + group.id">[Select Class: @{{ group.class_name }}]
                                                </option>
                                                <option v-for="student in group.students" :value="'student_' + student.id"
                                                    :key="student.id">
                                                    @{{ student.name }}
                                                </option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div v-if="selectConfig[type]" class="form-group">
                                    <label class="col-sm-2 control-label">@{{ selectConfig[type].label }}</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" :name="selectConfig[type].name"
                                            v-model="dynamicSelectModel" required>
                                            <option value="">-- Select --</option>
                                            <option v-if="selectConfig[type].showAllOption" value="all">-- All --
                                            </option>
                                            <option v-for="item in getOptions(type)" :value="item.id">
                                                @{{ item.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-8" style="margin-bottom: 10px;">
                                        <template v-if="tokenMap[type] && tokenMap[type].length">
                                            <small class="text-muted">
                                                Click a token to insert it into the message.
                                            </small>
                                            <span v-for="token in tokenMap[type]" :key="token.token" class="label"
                                                :class="'label-' + token.color"
                                                style="cursor:pointer; margin-right: 5px; margin-bottom: 5px; display: inline-block;color: white;font-size: small;"
                                                @click="insertToken(token.token)">
                                                @{{ token.label }}
                                            </span>
                                        </template>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Message</label>
                                    <div class="col-sm-8">
                                        <textarea name="message" class="form-control" v-model="message" rows="4" required ref="messageBox"></textarea>
                                        <small class="text-muted">
                                            You can use <code>{variable}</code> in your message. It will be replaced
                                            automatically.
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-8" style="margin-bottom: 10px;">
                                        <small class="text-muted d-block mb-2 font-weight-bold">
                                            Notification Settings
                                        </small>
                                        <div class="d-flex flex-wrap gap-3">
                                            <label class="radio-inline" style="margin-right: 15px;">
                                                <input disabled type="checkbox" {{$notificationSettings['mail'] == 1 ? 'checked' : '' }}> Mail
                                            </label>
                                            <label class="radio-inline" style="margin-right: 15px;">
                                                <input disabled type="checkbox" {{$notificationSettings['sms'] == 1 ? 'checked' : '' }}> SMS
                                            </label>
                                            <label class="radio-inline" style="margin-right: 15px;">
                                                <input disabled type="checkbox" {{$notificationSettings['whatsapp'] == 1 ? 'checked' : '' }}> WhatsApp
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" v-model="type" name="type" />
                                <input v-if="type === 'students'" type="hidden" v-model="selectedStudentId"
                                    name="selected_student_id" />
                                <input v-if="type === 'students'"type="hidden" v-model="selectedClassId"
                                    name="selected_class_id" />
                                <input type="hidden" v-model="message" name="message" />

                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-6">
                                        <button class="btn btn-primary" type="submit">
                                            <span class="glyphicon glyphicon-send"></span> Send
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <!-- Mainly scripts -->
    <script src="{{ URL::to('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/dataTables/datatables.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/axios-1.11.0/axios.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}", "Validation Error");
            @endforeach
        </script>
    @endif
@endsection

@section('vue')
    <script type="text/javascript">
        var app = new Vue({
            el: "#app",
            data() {
                return {
                    type: '',
                    message: '',
                    studentsByClass: [],
                    guardians: [],
                    teachers: [],
                    employees: [],
                    selectedType: '',
                    selectedClassName: '',
                    selectedStudent: null,
                    selectedId: '',
                    selectedClassId: '',
                    selectedStudentId: '',
                    selectedGuardianId: '',
                    selectedTeacherId: '',
                    selectedEmployeeId: '',
                    selectConfig: {
                        guardians: {
                            label: 'Guardian',
                            optionsKey: 'guardians',
                            model: 'selectedGuardianId',
                            name: 'selected_guardian_id',
                            showAllOption: true,
                        },
                        teachers: {
                            label: 'Teacher',
                            optionsKey: 'teachers',
                            model: 'selectedTeacherId',
                            name: 'selected_teacher_id',
                            showAllOption: true,
                        },
                        employees: {
                            label: 'Employee',
                            optionsKey: 'employees',
                            model: 'selectedEmployeeId',
                            name: 'selected_employee_id',
                            showAllOption: true,
                        }
                    },

                    // tokens
                    tokenMap: {
                        students: [{
                                token: '{student_name}',
                                label: 'Student Name',
                                color: 'student-token'
                            },
                            {
                                token: '{class_name}',
                                label: 'Class Name',
                                color: 'student-token'
                            },
                            {
                                token: '{gr_no}',
                                label: 'Gr No',
                                color: 'student-token'
                            },
                            {
                                token: '{section_name}',
                                label: 'Section Name',
                                color: 'student-token'
                            },
                            {
                                token: '{father_name}',
                                label: 'Father Name',
                                color: 'student-token'
                            },
                            {
                                token: '{address}',
                                label: 'Address',
                                color: 'student-token'
                            },
                            {
                                token: '{gender}',
                                label: 'Gender',
                                color: 'student-token'
                            },
                        ],
                        teachers: [{
                                token: '{teacher_name}',
                                label: 'Teacher Name',
                                color: 'teacher-token'
                            },
                            {
                                token: '{qualification}',
                                label: 'Qualification',
                                color: 'teacher-token'
                            },
                            {
                                token: '{gender}',
                                label: 'Gender',
                                color: 'teacher-token'
                            },
                            {
                                token: '{address}',
                                label: 'Address',
                                color: 'teacher-token'
                            },
                            {
                                token: '{phone}',
                                label: 'Phone',
                                color: 'teacher-token'
                            },
                            {
                                token: '{subject}',
                                label: 'Subject',
                                color: 'teacher-token'
                            },
                        ],
                        guardians: [{
                                token: '{guardian_name}',
                                label: 'Guardian Name',
                                color: 'guardian-token'
                            },
                            {
                                token: '{email}',
                                label: 'Email',
                                color: 'guardian-token'
                            },
                            {
                                token: '{phone}',
                                label: 'Phone',
                                color: 'guardian-token'
                            },
                            {
                                token: '{address}',
                                label: 'Address',
                                color: 'guardian-token'
                            },
                            {
                                token: '{profession}',
                                label: 'Profession',
                                color: 'guardian-token'
                            },
                            {
                                token: '{income}',
                                label: 'Income',
                                color: 'guardian-token'
                            },
                        ],
                        employees: [{
                                token: '{employee_name}',
                                label: 'Employee Name',
                                color: 'employee-token'
                            },
                            {
                                token: '{qualification}',
                                label: 'Qualification',
                                color: 'employee-token'
                            },
                            {
                                token: '{gender}',
                                label: 'Gender',
                                color: 'employee-token'
                            },
                            {
                                token: '{address}',
                                label: 'Address',
                                color: 'employee-token'
                            },
                            {
                                token: '{email}',
                                label: 'Email',
                                color: 'employee-token'
                            },
                            {
                                token: '{role}',
                                label: 'Role',
                                color: 'employee-token'
                            },
                            {
                                token: '{phone}',
                                label: 'Phone',
                                color: 'employee-token'
                            },
                        ],
                    }

                };
            },
            methods: {
                handleTypeChange() {
                    this.message = '';
                    this.getData();
                    console.log("Selected type changed to:", this.type);
                },
                getData() {
                    axios.post('/msg-notifications/get/data', {
                            type: this.type
                        })
                        .then(response => {
                            const res = response.data;
                            switch (this.type) {
                                case 'students':
                                    this.studentsByClass = response.data;
                                    break;
                                case 'teachers':
                                    this.teachers = response.data;
                                    break;
                                case 'guardians':
                                    this.guardians = response.data;
                                    console.log(this.guardians);

                                    break;
                                case 'employees':
                                    this.employees = response.data;
                                    break;
                            }
                        })
                        .catch(error => {
                            console.error('Failed to fetch', error);
                        });
                },
                onSelectionChangeStudents() {
                    if (this.selectedId.startsWith('class_')) {
                        const classId = parseInt(this.selectedId.replace('class_', ''));
                        const selectedClass = this.studentsByClass.find(c => c.id === classId);

                        this.selectedType = 'class';
                        this.selectedClassId = classId;
                        this.selectedClassName = selectedClass?.class_name || '';
                        this.selectedStudent = null;

                        // console.log('Selected Class:', this.selectedClassName, 'ID:', this.selectedClassId);

                    } else if (this.selectedId.startsWith('student_')) {
                        const studentId = parseInt(this.selectedId.replace('student_', ''));
                        for (let group of this.studentsByClass) {
                            const student = group.students.find(s => s.id === studentId);
                            if (student) {
                                this.selectedType = 'student';
                                this.selectedStudent = student;
                                this.selectedClassId = null;
                                this.selectedClassName = '';
                                this.selectedStudentId = student.id;
                                // console.log('Selected Student:', student.name, 'ID:', student.id);
                                break;
                            }
                        }
                    } else {
                        // Reset all
                        this.selectedType = '';
                        this.selectedClassId = null;
                        this.selectedClassName = '';
                        this.selectedStudent = null;
                    }
                },
                insertToken(token) {
                    const textarea = this.$refs.messageBox;

                    if (textarea) {
                        const start = textarea.selectionStart;
                        const end = textarea.selectionEnd;
                        const before = this.message.substring(0, start);
                        const after = this.message.substring(end);
                        this.message = before + token + after;
                        this.$nextTick(() => {
                            textarea.focus();
                            textarea.selectionStart = textarea.selectionEnd = start + token.length;
                        });
                    }
                },
                getModelBinding(type) {
                    return this[this.selectConfig[type].model];
                },
                getOptions(type) {
                    return this[this.selectConfig[type].optionsKey] || [];
                }
            },
            computed: {
                dynamicSelectModel: {
                    get() {
                        return this[this.selectConfig[this.type]?.model];
                    },
                    set(val) {
                        this[this.selectConfig[this.type]?.model] = val;
                    }
                }
            }
        });
    </script>
@endsection
