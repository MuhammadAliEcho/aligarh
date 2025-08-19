@extends('admin.layouts.master')

@section('title', 'Logs |')

@section('head')
    <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <style>
        .notification-card {
            background: #ffffff;
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            transition: background 0.2s ease, transform 0.2s ease;
            position: relative;
            margin-bottom: 8px;
            width: 60%;
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 12px;
            animation: fadeInUp 0.4s ease-out;
        }

        .notification-card:hover {
            background: #f5f6f5;
            transform: translateY(-2px);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 18px;
            color: white;
            background: #1ab394;
            /* background: #1877f2; */
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .notification-message {
            font-size: 14px;
            font-weight: 600;
            color: #1c2526;
            margin: 0;
            line-height: 1.4;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }

        .notification-details {
            font-size: 12px;
            color: #65676b;
            margin: 2px 0 0;
            line-height: 1.2;
        }

        .notification-date-badge {
            font-size: 12px;
            color: #65676b;
            font-weight: 400;
            margin-left: 8px;
        }

        .notification-header {
            display: none;
        }

        .notification-body {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0;
        }

        .notification-item,
        .notification-item-remark {
            display: none;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .m-2 {
            margin: 0.5rem 0;
        }

        .font-small {
            font-size: 0.75rem;
        }

        .pagination nav {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: inline-flex;
            padding-left: 0;
            margin: 10px 0;
            border-radius: 4px;
        }

        .status-badge {
            background-color: #42b72a;
            color: white;
            margin-left: 6px;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 10px;
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
                    <li class="active"><a>Logs</a></li>
                </ol>
            </div>
            @can('user-settings.change.session')
                <div class="col-lg-4 col-md-6">
                    @include('admin.includes.academic_session')
                </div>
            @endcan
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <div class="content">
                            <div id="app">
                                <div class="container mt-4 d-flex flex-wrap gap-3">
                                    <div v-for="notification in notifications" :key="notification.id"
                                        class="container notification-card" :class="{ 'unread': !notification.is_read }">

                                        <div class="notification-icon">
                                            <span><i class="fa fa-bell"></i></span>
                                        </div>

                                        <div class="notification-content">
                                            <div class="notification-message">
                                                @{{ truncate(notification.notification, 50) }}
                                            </div>
                                            <div class="notification-details">
                                                From: @{{ notification.user ? notification.user.name : 'System' }} •
                                                @{{ formatTime(notification.created_at) }}
                                                <span v-if="!notification.is_read" class="status-badge">New</span>
                                                <a :href="notification.link" class="notification-link font-small">View
                                                    Details</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="current_page < last_page" class="text-center mt-3 w-100">
                                        <button @click="loadMore" class="see-more-btn btn btn-primary"
                                            :disabled="isSubmitting">
                                            See More
                                        </button>
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
@section('vue')
    <script src="{{ URL::to('src/js/plugins/axios-1.11.0/axios.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/moment/moment.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Vue({
                el: '#app',
                data: {
                    notifications: @json($notifications),
                    per_page: 10,
                    current_page: @json($pagination['current_page']),
                    last_page: @json($pagination['last_page']),
                    from: @json($pagination['from']),
                    to: @json($pagination['to']),
                    total: @json($pagination['total']),
                    isSubmitting: false
                },
                methods: {
                    truncate(text, length) {
                        return text.length <= length ? text : text.substring(0, length) + '...';
                    },
                    formatTime(date) {
                        return moment(date).fromNow();
                    },
                    loadMore() {
                        if (this.isSubmitting || this.current_page >= this.last_page) return;
                        this.isSubmitting = true;

                        axios.get('/notifications/logs', {
                                params: {
                                    page: this.current_page + 1,
                                    per_page: this.per_page
                                }
                            })
                            .then((response) => {
                                this.notifications = this.notifications.concat(response.data
                                    .notifications);
                                this.current_page = response.data.current_page;
                                this.last_page = response.data.last_page;
                                this.from = response.data.from;
                                this.to = response.data.to;
                                this.total = response.data.total;
                            })
                            .catch((error) => {
                                console.error('Load error:', error);
                                swal('Error', 'Failed to load notifications', 'error');
                            })
                            .finally(() => {
                                this.isSubmitting = false;
                            });
                    }
                },
                mounted() {
                    this.last_page = Math.ceil(this.total / this.per_page);
                }
            });
        });
    </script>
@endsection
