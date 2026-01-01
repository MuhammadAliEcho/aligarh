@extends('admin.layouts.master')

@section('title', __('modules.pages_bulk_marks_entry').' |')

@section('head')
<link href="{{ asset('src/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
<style>
    .bulk-marks-table {
        overflow-x: auto;
        display: block;
        white-space: nowrap;
        max-width: 100%;
    }
    .bulk-marks-table table {
        min-width: 100%;
    }
    .bulk-marks-table th.sticky-col,
    .bulk-marks-table td.sticky-col {
        position: sticky;
        left: 0;
        z-index: 10;
        background-color: #fff;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    }
    .bulk-marks-table th.sticky-col:nth-child(2),
    .bulk-marks-table td.sticky-col:nth-child(2) {
        left: 80px;
        z-index: 9;
    }
    .bulk-marks-table thead th.sticky-col {
        z-index: 11;
        background-color: #f9f9f9;
    }
    .subject-group {
        border-left: 2px solid #1ab394;
    }
    .marks-input {
        width: 90px;
        text-align: center;
        padding: 6px 10px;
    }
    .attendance-checkbox {
        margin: 5px 0 0 0;
        display: block;
    }
    .student-total {
        font-weight: bold;
        background-color: #f9f9f9;
    }
    .validation-error {
        border-color: #ed5565 !important;
    }
    .subject-visibility-toggle {
        max-height: 300px;
        overflow-y: auto;
    }
</style>
@endsection

@section('content')

@include('admin.includes.side_navbar')

<div id="page-wrapper" class="gray-bg">

    @include('admin.includes.top_navbar')

    <!-- Heading -->
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8 col-md-6">
            <h2>{{ __('modules.pages_bulk_marks_entry') }}</h2>
            <ol class="breadcrumb">
                <li>{{ __('common.home') }}</li>
                <li>
                    <a href="{{ route('manage-result.index') }}">{{ __('modules.pages_student_results_manage_title') }}</a>
                </li>
                <li class="active">
                    <strong>{{ __('modules.pages_bulk_marks_entry') }}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4 col-md-6">
            @include('admin.includes.academic_session')
        </div>
    </div>

    <!-- main Section -->
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li>
                            <a href="{{ route('manage-result.index') }}"><span class="fa fa-list"></span> {{ __('modules.pages_make_result') }}</a>
                        </li>
                        <li class="active">
                            <a data-toggle="tab" href="#tab-bulk"><span class="fa fa-table"></span> {{ __('modules.pages_bulk_marks_entry') }}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab-bulk" class="tab-pane active">
                            <div class="panel-body" style="min-height: 400px">
                                <h2>{{ __('modules.pages_bulk_marks_entry') }}</h2>
                                <div class="hr-line-dashed"></div>
                                
                                <!-- Selection Form -->
                                <form class="form-horizontal jumbotron" @submit.prevent="loadBulkData">
            <div class="form-group">
                <label class="col-md-2 control-label">{{ __('labels.exam') }}</label>
                <div class="col-md-4">
                    <select v-model="selectedExam" class="form-control select2" required>
                        <option value="">{{ __('labels.select_exam') }}</option>
                        @foreach($exams as $exam)
                        <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="col-md-2 control-label">{{ __('labels.class') }}</label>
                <div class="col-md-4">
                    <select v-model="selectedClass" class="form-control select2" required>
                        <option value="">{{ __('labels.select_class') }}</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-6">
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <i class="fa fa-search"></i> {{ __('common.load_students') }}
                    </button>
                </div>
            </div>
        </form>

        <div class="hr-line-dashed"></div>

        <!-- Loading Indicator -->
        <div v-if="loading" class="text-center">
            <i class="fa fa-spinner fa-spin fa-3x text-primary"></i>
            <p class="text-muted">{{ __('common.loading') }}...</p>
        </div>

        <!-- Bulk Marks Entry Grid -->
        <div v-if="dataLoaded && !loading">
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> 
                {{ __('modules.bulk_marks_entry_instructions') }}
            </div>

            <!-- Subject Visibility Controls -->
            <div v-if="subjects.length > 0" class="panel panel-default tw-mb-4">
                <div class="panel-heading">
                    <i class="fa fa-eye"></i> Show/Hide Subjects
                    <button type="button" class="btn btn-xs btn-primary pull-right" @click="toggleAllSubjects(true)">Show All</button>
                    <button type="button" class="btn btn-xs btn-default pull-right tw-mr-2" @click="toggleAllSubjects(false)">Hide All</button>
                </div>
                <div class="panel-body subject-visibility-toggle">
                    <div class="row">
                        <div class="col-md-3 tw-mb-2" v-for="subject in subjects" :key="'toggle-' + subject.id">
                            <label class="tw-cursor-pointer">
                                <input type="checkbox" v-model="visibleSubjects[subject.id]">
                                @{{ subject.name }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Result Attributes Configuration -->
            <div v-if="visibleSubjectsList.length > 0" class="panel panel-success tw-mb-4">
                <div class="panel-heading">
                    <i class="fa fa-cog"></i> Result Attributes Configuration
                    <small class="text-muted">(Configure attributes for visible subjects)</small>
                </div>
                <div class="panel-body">
                    <div v-for="subject in visibleSubjectsList" :key="'attr-' + subject.id" class="tw-mb-4">
                        <h4 class="tw-font-bold tw-mb-2">@{{ subject.name }}</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="50%">{{ __('labels.result_attribute') }}</th>
                                    <th width="30%">{{ __('labels.marks') }}</th>
                                    <th width="20%" class="text-center">
                                        <button type="button" class="btn btn-xs btn-success" @click="addAttributeRow(subject.id)" title="{{ __('labels.add') }}">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-xs btn-danger" @click="removeAttributeRow(subject.id)" title="{{ __('labels.remove') }}">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(attr, index) in subjectAttributes[subject.id]" :key="'attr-' + subject.id + '-' + index">
                                    <td>
                                        <input type="text" class="form-control" v-model="attr.name" placeholder="e.g., Written, Oral, Practical" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" v-model.number="attr.marks" min="0" step="0.5" placeholder="Marks" required>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-warning" @click="removeSpecificAttributeRow(subject.id, index)" title="Remove this row">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('labels.total_marks') }}</strong></td>
                                    <td colspan="2"><strong>@{{ calculateAttributeTotal(subject.id) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

                                <form @submit.prevent="saveBulkMarks">
                                    <div class="bulk-marks-table">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="sticky-col" rowspan="2">{{ __('labels.gr_no') }}</th>
                                <th class="sticky-col" rowspan="2">{{ __('labels.student_name') }}</th>
                                <template v-for="subject in visibleSubjectsList">
                                    <th v-if="subjectAttributes[subject.id] && subjectAttributes[subject.id].length > 0" 
                                        :key="subject.id" 
                                        :colspan="subjectAttributes[subject.id].length + 1"
                                        class="text-center subject-group">
                                        @{{ subject.name }}
                                    </th>
                                </template>
                                <th rowspan="2" class="text-center">{{ __('labels.total_marks') }}</th>
                            </tr>
                            <tr>
                                <template v-for="subject in visibleSubjectsList">
                                    <template v-if="subjectAttributes[subject.id] && subjectAttributes[subject.id].length > 0">
                                        <th v-for="(attr, attrIndex) in subjectAttributes[subject.id]" 
                                            :key="subject.id + '-' + attrIndex"
                                            class="text-center">
                                            @{{ attr.name }}<br>
                                            <small class="text-muted">(@{{ attr.marks }})</small>
                                        </th>
                                        <th :key="subject.id + '-total'" class="text-center">
                                            {{ __('labels.total') }}<br>
                                            <small class="text-muted">(@{{ calculateAttributeTotal(subject.id) }})</small>
                                        </th>
                                    </template>
                                </template>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="student in students" :key="student.id">
                                <td class="sticky-col"><strong>@{{ student.gr_no }}</strong></td>
                                <td class="sticky-col">
                                    @{{ student.name }}
                                    <i v-if="student.student_subject_result && student.student_subject_result.length > 0" 
                                       class="fa fa-check-circle text-success" 
                                       title="Has existing marks" 
                                       style="margin-left: 5px;"></i>
                                </td>
                                
                                <template v-for="subject in visibleSubjectsList">
                                    <template v-if="subjectAttributes[subject.id] && bulkMarks[student.id] && bulkMarks[student.id][subject.id]">
                                        <td v-for="(attr, attrIndex) in subjectAttributes[subject.id]" 
                                            :key="subject.id + '-' + student.id + '-' + attrIndex"
                                            class="text-center">
                                            <input type="number" 
                                                v-model.number="bulkMarks[student.id][subject.id].attributes[attrIndex].marks"
                                                :max="attr.marks"
                                                min="0"
                                                step="0.5"
                                                class="form-control marks-input"
                                                :class="{'validation-error': !isValidMark(bulkMarks[student.id][subject.id].attributes[attrIndex].marks, attr.marks)}"
                                                :disabled="!bulkMarks[student.id][subject.id].attributes[attrIndex].attendance">
                                            <label class="attendance-checkbox tw-cursor-pointer">
                                                <input type="checkbox" 
                                                    v-model="bulkMarks[student.id][subject.id].attributes[attrIndex].attendance"
                                                    @change="toggleAttendance(student.id, subject.id, attrIndex)">
                                                <small>{{ __('labels.present') }}</small>
                                            </label>
                                        </td>
                                        <td :key="subject.id + '-' + student.id + '-total'" 
                                            class="text-center student-total">
                                            @{{ calculateSubjectTotal(student.id, subject.id) }} / @{{ calculateAttributeTotal(subject.id) }}
                                        </td>
                                    </template>
                                </template>
                                
                                <td class="text-center student-total">
                                    <strong>@{{ calculateStudentGrandTotal(student.id) }}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                                    </div>

                                    <div class="form-group text-center tw-mt-5">
                                        <button type="submit" class="btn btn-primary btn-lg" :disabled="saving || !isFormValid">
                                            <i class="fa fa-save"></i> {{ __('common.save_marks') }}
                                        </button>
                                        <button type="button" class="btn btn-default btn-lg tw-ml-2" @click="resetForm">
                                            <i class="fa fa-refresh"></i> {{ __('common.reset') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

@endsection

@section('script')
{{-- <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> --}}

<script type="text/javascript">
$(document).ready(function() {
    // Configure axios defaults
    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    // Initialize select2
    // $('.select2').select2();

    $('[data-toggle="tooltip"]').tooltip();
});
</script>

@endsection

@section('vue')
<script type="text/javascript">
var bulkMarksApp = new Vue({
    el: '#app',
    data: {
        selectedExam: '',
        selectedClass: '',
        loading: false,
        saving: false,
        dataLoaded: false,
        exam: null,
        class: null,
        subjects: [],
        students: [],
        bulkMarks: {},
        visibleSubjects: {},
        subjectAttributes: {}, // Tracks attributes for each subject
    },
    computed: {
        subjectsWithAttributes() {
            return this.subjects.filter(s => this.subjectAttributes[s.id] && this.subjectAttributes[s.id].length > 0);
        },
        visibleSubjectsList() {
            return this.subjects.filter(s => this.visibleSubjects[s.id]);
        },
        isFormValid() {
            // Check if data is loaded
            if (!this.dataLoaded || this.visibleSubjectsList.length === 0) {
                return false;
            }
            
            // Check if all visible subjects have attributes configured
            for (let subject of this.visibleSubjectsList) {
                if (!this.subjectAttributes[subject.id] || this.subjectAttributes[subject.id].length === 0) {
                    return false;
                }
                // Check if attribute names and marks are filled
                for (let attr of this.subjectAttributes[subject.id]) {
                    if (!attr.name || !attr.name.trim() || attr.marks <= 0) {
                        return false;
                    }
                }
            }
            
            // Check student marks validity (only for students with attendance marked)
            for (let studentId in this.bulkMarks) {
                for (let subjectId in this.bulkMarks[studentId]) {
                    if (!this.visibleSubjects[subjectId]) continue;
                    const attrs = this.bulkMarks[studentId][subjectId].attributes;
                    if (!attrs) continue;
                    
                    for (let i = 0; i < attrs.length; i++) {
                        // Only validate marks if student is marked as present
                        if (attrs[i].attendance) {
                            if (!this.isValidMark(attrs[i].marks, attrs[i].maxMarks)) {
                                return false;
                            }
                        }
                    }
                }
            }
            return true;
        }
    },
    methods: {
        getAttributesArray(attributes) {
            return typeof attributes === 'string' ? JSON.parse(attributes) : attributes;
        },
        toggleAllSubjects(visible) {
            this.subjects.forEach(subject => {
                this.$set(this.visibleSubjects, subject.id, visible);
            });
        },
        loadBulkData() {
            if (!this.selectedExam || !this.selectedClass) {
                toastr.warning('Please select exam and class');
                return;
            }

            this.loading = true;
            axios.post('{{ route("manage-result.bulk.get.students") }}', {
                exam: this.selectedExam,
                class: this.selectedClass
            })
            .then(response => {
                console.log('Response received:', response.data);
                if (response.data.success) {
                    this.exam = response.data.exam;
                    this.class = response.data.class;
                    this.subjects = response.data.subjects || [];
                    this.students = response.data.students || [];
                    
                    console.log('Subjects loaded:', this.subjects.length);
                    console.log('Students loaded:', this.students.length);
                    
                    if (this.students.length === 0) {
                        toastr.warning('No students found for selected class');
                        this.dataLoaded = false;
                        return;
                    }
                    
                    // Show stats about loaded data
                    if (response.data.stats) {
                        const stats = response.data.stats;
                        if (stats.students_with_results > 0) {
                            toastr.info(`Loaded ${stats.total_students} students, ${stats.students_with_results} have existing marks`);
                        }
                    }
                    
                    // Initialize all subjects as visible and load their attributes
                    this.visibleSubjects = {};
                    this.subjectAttributes = {};
                    
                    this.subjects.forEach(subject => {
                        this.$set(this.visibleSubjects, subject.id, true);
                        
                        // Load existing attributes or initialize empty
                        if (subject.subject_result_attribute && subject.subject_result_attribute.attributes) {
                            const attrs = typeof subject.subject_result_attribute.attributes === 'string'
                                ? JSON.parse(subject.subject_result_attribute.attributes)
                                : subject.subject_result_attribute.attributes;
                            this.$set(this.subjectAttributes, subject.id, attrs.map(a => ({name: a.name, marks: a.marks})));
                        } else {
                            this.$set(this.subjectAttributes, subject.id, [{name: '', marks: 0}]);
                        }
                    });
                    
                    try {
                        this.initializeBulkMarks();
                        this.dataLoaded = true;
                        toastr.success('Data loaded successfully!');
                    } catch (error) {
                        console.error('Error initializing bulk marks:', error);
                        toastr.error('Error initializing form: ' + error.message);
                        this.dataLoaded = false;
                    }
                } else {
                    toastr.error(response.data.message || 'Error loading data');
                }
            })
            .catch(error => {
                console.error('Error loading data:', error);
                if (error.response) {
                    console.error('Error response:', error.response.data);
                    toastr.error(error.response.data.message || 'Error loading data');
                } else {
                    toastr.error('Error loading data');
                }
                this.dataLoaded = false;
            })
            .finally(() => {
                this.loading = false;
            });
        },
        initializeBulkMarks() {
            console.log('Initializing bulk marks...');
            this.bulkMarks = {};
            
            this.students.forEach(student => {
                this.bulkMarks[student.id] = {};
                this.subjects.forEach(subject => {
                    try {
                        const attributes = this.subjectAttributes[subject.id] || [];
                        
                        // Find existing result for this subject (using snake_case property name)
                        const existingResult = (student.student_subject_result && Array.isArray(student.student_subject_result)) 
                            ? student.student_subject_result.find(
                                r => r.subject_result_attribute && r.subject_result_attribute.subject_id == subject.id
                            )
                            : null;

                        this.bulkMarks[student.id][subject.id] = {
                            attributes: attributes.map((attr, index) => {
                                let marks = 0;
                                let attendance = true;
                                let hasOldData = false;
                                
                                if (existingResult && existingResult.obtain_marks) {
                                    const obtainMarks = typeof existingResult.obtain_marks === 'string'
                                        ? JSON.parse(existingResult.obtain_marks)
                                        : existingResult.obtain_marks;
                                    
                                    // Try to match by attribute name first (handles structure changes)
                                    const matchedMark = obtainMarks.find(om => om.name === attr.name) || obtainMarks[index];
                                    
                                    if (matchedMark) {
                                        marks = parseFloat(matchedMark.marks) || 0;
                                        attendance = matchedMark.attendance !== false;
                                        hasOldData = true;
                                    }
                                }
                                
                                return {
                                    name: attr.name,
                                    maxMarks: attr.marks,
                                    marks: marks,
                                    attendance: attendance,
                                    hasOldData: hasOldData
                                };
                            })
                        };
                    } catch (error) {
                        console.error('Error processing subject:', subject.name, error);
                    }
                });
            });
            
            console.log('Bulk marks initialized:', Object.keys(this.bulkMarks).length, 'students');
        },
        toggleAttendance(studentId, subjectId, attrIndex) {
            // When unchecking attendance (marking as absent), set marks to 0
            const attr = this.bulkMarks[studentId][subjectId].attributes[attrIndex];
            if (!attr.attendance) {
                attr.marks = 0;
            }
        },
        calculateSubjectTotal(studentId, subjectId) {
            if (!this.bulkMarks[studentId] || !this.bulkMarks[studentId][subjectId]) {
                return 0;
            }
            return this.bulkMarks[studentId][subjectId].attributes.reduce((sum, attr) => {
                return sum + (attr.attendance ? (parseFloat(attr.marks) || 0) : 0);
            }, 0);
        },
        calculateStudentGrandTotal(studentId) {
            let total = 0;
            this.subjects.forEach(subject => {
                if (this.visibleSubjects[subject.id]) {
                    total += this.calculateSubjectTotal(studentId, subject.id);
                }
            });
            return total;
        },
        isValidMark(mark, maxMark) {
            const m = parseFloat(mark);
            return !isNaN(m) && m >= 0 && m <= maxMark;
        },
        calculateAttributeTotal(subjectId) {
            if (!this.subjectAttributes[subjectId]) return 0;
            return this.subjectAttributes[subjectId].reduce((sum, attr) => sum + (parseFloat(attr.marks) || 0), 0);
        },
        updateBulkMarksForSubject(subjectId) {
            // Update bulkMarks structure for all students when attributes change
            if (!this.dataLoaded) return;
            
            const newAttributes = this.subjectAttributes[subjectId] || [];
            
            this.students.forEach(student => {
                if (!this.bulkMarks[student.id]) {
                    this.bulkMarks[student.id] = {};
                }
                
                const oldData = this.bulkMarks[student.id][subjectId]?.attributes || [];
                
                // Create new attributes array, preserving existing marks where possible
                const newAttributesData = newAttributes.map((attr, index) => {
                    if (oldData[index]) {
                        // Keep existing data but update maxMarks
                        return {
                            name: attr.name,
                            maxMarks: attr.marks,
                            marks: oldData[index].marks,
                            attendance: oldData[index].attendance
                        };
                    } else {
                        // New attribute
                        return {
                            name: attr.name,
                            maxMarks: attr.marks,
                            marks: 0,
                            attendance: true
                        };
                    }
                });
                
                this.$set(this.bulkMarks[student.id], subjectId, {
                    attributes: newAttributesData
                });
            });
        },
        addAttributeRow(subjectId) {
            if (!this.subjectAttributes[subjectId]) {
                this.$set(this.subjectAttributes, subjectId, []);
            }
            this.subjectAttributes[subjectId].push({name: '', marks: 0});
            this.updateBulkMarksForSubject(subjectId);
        },
        removeAttributeRow(subjectId) {
            if (this.subjectAttributes[subjectId] && this.subjectAttributes[subjectId].length > 1) {
                this.subjectAttributes[subjectId].pop();
                this.updateBulkMarksForSubject(subjectId);
            }
        },
        removeSpecificAttributeRow(subjectId, index) {
            if (this.subjectAttributes[subjectId] && this.subjectAttributes[subjectId].length > 1) {
                this.subjectAttributes[subjectId].splice(index, 1);
                this.updateBulkMarksForSubject(subjectId);
            }
        },
        saveBulkMarks() {
            if (!this.isFormValid) {
                toastr.error('Please configure attributes for all visible subjects');
                return;
            }

            this.saving = true;

            const bulkData = {};
            const attributesData = {};
            
            // Prepare data for visible subjects only
            this.visibleSubjectsList.forEach(subject => {
                const attributes = this.subjectAttributes[subject.id] || [];
                const totalMarks = this.calculateAttributeTotal(subject.id);
                
                // Prepare attributes data
                attributesData[subject.id] = {
                    attributes: attributes,
                    total_marks: totalMarks
                };
                
                // Prepare bulk marks data
                bulkData[subject.id] = {
                    total_marks: totalMarks,
                    attributes: attributes,
                    students: {}
                };

                this.students.forEach(student => {
                    bulkData[subject.id].students[student.id] = {
                        obtain_marks: this.bulkMarks[student.id][subject.id].attributes
                    };
                });
            });

            axios.post('{{ route("manage-result.bulk.store") }}', {
                exam: this.selectedExam,
                class: this.selectedClass,
                attributes_data: attributesData,
                bulk_data: bulkData
            })
            .then(response => {
                if (response.data.success) {
                    toastr.success(response.data.msg || 'Attributes and marks saved successfully!');
                    this.loadBulkData();
                }
            })
            .catch(error => {
                console.error(error);
                if (error.response && error.response.data) {
                    toastr.error(error.response.data.msg || 'Error saving data');
                } else {
                    toastr.error('Error saving data');
                }
            })
            .finally(() => {
                this.saving = false;
            });
        },
        resetForm() {
            this.selectedExam = '';
            this.selectedClass = '';
            this.dataLoaded = false;
            this.subjects = [];
            this.students = [];
            this.bulkMarks = {};
            this.visibleSubjects = {};
            this.subjectAttributes = {};
        }
    }
});
</script>
@endsection
