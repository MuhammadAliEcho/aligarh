<div class="row border-bottom">
    <nav class="navbar navbar-static-top ng-scope" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " id="nav_collapse" href="#"><i
                    class="fa fa-bars"></i> </a>
            <!--     <div role="search" style="padding: 15px 0px 0px 0px; float: left">
        <h3>Current Session: 2017-2018</h3>
    </div> -->
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <span class="m-r-sm text-muted welcome-message">Welcome to Aligarh Management System.</span>
            </li>

            <li class="nb-dropdown" id="nb-bell">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="fa fa-bell"></i>
                    <span class="nb-badge-unread" id="nb-unread-count"></span>
                </a>

                <div class="nb-dropdown dropdown-menu">
                    <div id="nb-list"></div> <!-- Notifications will be appended here -->
                    <div class="nb-mini-footer">
                        <a href="{{ route('notifications.log') }}">See all notifications</a>
                    </div>
                </div>
            </li>


            <li>
                <a href="{{ URL('logout') }}">
                    <i class="fa fa-sign-out"></i> Log Out
                </a>
            </li>

        </ul>

    </nav>
    <!-- <script type="text/javascript">
        /*  $(document).ready(function(){

                                                                        $("#nav_collapse").click(function(){

                                                                            $.post('{{ URL('user-settings/skincfg') }}', { _token: "{{ csrf_token() }}", nav_collapse: "mini-navbar" })
                                                                            .done(function(data) {
                                                                                    toastr.options = {
                                                                                        closeButton: true,
                                                                                        progressBar: true,
                                                                                        showMethod: 'slideDown',
                                                                                        timeOut: 8000
                                                                                    };
                                                                                    toastr.success(data.toastrmsg.msg, data.toastrmsg.title);
                                                                              }
                                                                            )
                                                                            .fail(function () {
                                                                                alert("Fail");
                                                                            });

                                                                        });

                                                                      });*/
    </script> -->
</div>
<script src="{{ asset('src/js/plugins/axios-1.11.0/axios.min.js') }}"></script>
<script src="{{ asset('src/js/plugins/moment/moment.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if ($('#nb-bell').length) {
            // Load notifications from the server
            function loadNotifications() {
                $.ajax({
                    url: `{{ route('notifications.log') }}`,
                    method: 'GET',
                    data: {
                        per_page: 5
                    },
                    headers: {
                        'accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.notifications && Array.isArray(response.notifications)) {
                            const notifications = response.notifications;

                            const unreadCount = notifications.filter(n => !n.is_read).length;
                            $('#nb-unread-count').text(unreadCount > 0 ? unreadCount : '').toggle(unreadCount > 0);

                            const notificationList = $('#nb-list');
                            notificationList.empty(); // Clear existing notifications
                            notifications.slice(0, 5).forEach(notification => {
                                const notificationText = notification.notification || 'No message available';
                                const notificationUser = notification.user ? notification.user.name : 'System';
                                const notificationTime = formatTime(notification.created_at || '');

                                let notificationStatusHtml = '';

                                if (notification.is_read === 0) {
                                    notificationStatusHtml = `<span class="nb-mini-status">New</span>`;
                                }

                                const notificationHtml = `
                                    <div class="nb-mini-card" data-id="${notification.id}" data-link="${notification.link}">
                                        <div class="nb-mini-content">
                                            <div class="nb-mini-message">
                                                ${truncate(notificationText, 40)}
                                            </div>
                                            <div class="nb-mini-meta">
                                                ${notificationUser} • ${notificationTime}
                                                ${notificationStatusHtml}
                                            </div>
                                        </div>
                                    </div>
                                `;

                                // Append to the container with id 'nb-list'
                                notificationList.append(notificationHtml);
                            });

                            // Add click event listener for each notification card
                            $('.nb-mini-card').click(function() {
                                const notificationId = $(this).data('id');
                                const notificationLink = $(this).data('link');
                                markAsRead(notificationId, notificationLink);
                            });
                        } else {
                            console.error('Invalid notification format', response);
                        }
                    },
                    error: function() {
                        console.error('Failed to load notifications');
                    }
                });
            }

            // Truncate function to shorten text
            function truncate(text, length) {
                if (!text) return '';
                return text.length <= length ? text : text.substring(0, length) + '...';
            }

            // Format time using moment.js
            function formatTime(date) {
                if (!date || !moment(date).isValid()) return 'Invalid date';
                return moment(date).fromNow();
            }

            // Function to mark the notification as read
            function markAsRead(notificationId, notificationLink) {
                $.ajax({
                    url: `/notifications/${notificationId}`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        // Mark as read and open the link in a new tab
                        window.open(notificationLink, '_blank');
                        loadNotifications();
                    },
                    error: function(error) {
                        console.error('Mark as read failed:', error);
                        toastr.error("Failed to open notification.");
                    }
                });
            }

            // Initial loading of notifications
            loadNotifications();
        } else {
            console.log("Notification bell not found.");
        }
    });
</script>
