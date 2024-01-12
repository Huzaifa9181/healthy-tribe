<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('admin/home') }}" class="brand-link">
        <img src="{{ asset('public/' . generalSetting()->logo) ?? url('public/admin/dist/img/AdminLTELogo.png') }}"
            alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ generalSetting()->title ?? 'Healthy Tribe' }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ Auth::user()->image ? asset('public/' . Auth::user()->image) : url('public/admin/dist/img/user2-160x160.jpg') }}" alt="User Image" class="img-circle elevation-2">
            </div>
            <div class="info">
                <h6 class="d-block text-white">{{Auth::user()->name ?? ''}}</h6>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ url('admin/home') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @if(Auth::user()->role_id == 1)
                <li class="nav-item {{ request()->routeIs('admin.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>
                            Admin
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.index') }}"
                                class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Admin</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if (Auth::user()->role_id == 2 || Auth::user()->role_id == 1)
                    <li class="nav-item {{ request()->routeIs('users.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Users
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}"
                                    class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('users.create') }}"
                                    class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('workout_cat.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('workout_cat.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-dumbbell"></i>
                            <p>
                                Workout Categories
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('workout_cat.index') }}"
                                    class="nav-link {{ request()->routeIs('workout_cat.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('workout_cat.create') }}"
                                    class="nav-link {{ request()->routeIs('workout_cat.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('trainer_video.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('trainer_video.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-video"></i>
                            <p>
                                Trainer Video
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('trainer_video.index') }}"
                                    class="nav-link {{ request()->routeIs('trainer_video.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('trainer_video.create') }}"
                                    class="nav-link {{ request()->routeIs('trainer_video.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('plan.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('plan.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-dumbbell"></i>
                            <p>
                                Workout Plans
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('plan.index') }}"
                                    class="nav-link {{ request()->routeIs('plan.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('plan.create') }}"
                                    class="nav-link {{ request()->routeIs('plan.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('plan_video.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('plan_video.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-video"></i>
                            <p>
                                Workout Plans Videos
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('plan_video.index') }}"
                                    class="nav-link {{ request()->routeIs('plan_video.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('plan_video.create') }}"
                                    class="nav-link {{ request()->routeIs('plan_video.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(Auth::user()->role_id == 1)
                    <li class="nav-item {{ request()->routeIs('subscription.*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->routeIs('subscription.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-check"></i>
                            <p>
                                Subcription
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('subscription.index') }}"
                                    class="nav-link {{ request()->routeIs('subscription.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('subscription.create') }}"
                                    class="nav-link {{ request()->routeIs('subscription.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('content.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('content.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-globe"></i>
                            <p>
                                App Content
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('content.index') }}"
                                    class="nav-link {{ request()->routeIs('content.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Update Content</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('article.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('article.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-edit"></i>
                            <p>
                                Articles
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('article.index') }}"
                                    class="nav-link {{ request()->routeIs('article.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('article.create') }}"
                                    class="nav-link {{ request()->routeIs('article.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('addiction.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('addiction.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-dumbbell"></i>
                            <p>
                                Addictions
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('addiction.index') }}"
                                    class="nav-link {{ request()->routeIs('addiction.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('addiction.create') }}"
                                    class="nav-link {{ request()->routeIs('addiction.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('question.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('question.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-question"></i>
                            <p>
                                Questions
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('question.index') }}"
                                    class="nav-link {{ request()->routeIs('question.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('question.create') }}"
                                    class="nav-link {{ request()->routeIs('question.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('challenge.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('challenge.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-dumbbell"></i>
                            <p>
                                Challenge
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('challenge.index') }}"
                                    class="nav-link {{ request()->routeIs('challenge.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('challenge.create') }}"
                                    class="nav-link {{ request()->routeIs('challenge.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('group.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('group.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Groups
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('group.index') }}"
                                    class="nav-link {{ request()->routeIs('group.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('group.create') }}"
                                    class="nav-link {{ request()->routeIs('group.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('motivation.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('motivation.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-dumbbell"></i>
                            <p>
                                Motivations
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('motivation.index') }}"
                                    class="nav-link {{ request()->routeIs('motivation.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('motivation.create') }}"
                                    class="nav-link {{ request()->routeIs('motivation.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('currency.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('currency.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill"></i>
                            <p>
                                Currency
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('currency.index') }}"
                                    class="nav-link {{ request()->routeIs('currency.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('currency.create') }}"
                                    class="nav-link {{ request()->routeIs('currency.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('achieve.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('currency.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bicycle"></i>
                            <p>
                                Achieve
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('achieve.index') }}"
                                    class="nav-link {{ request()->routeIs('achieve.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('achieve.create') }}"
                                    class="nav-link {{ request()->routeIs('achieve.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('chat.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-comment-alt"></i>
                            <p>
                                Agent Chat
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('chat.index') }}"
                                    class="nav-link {{ request()->routeIs('chat.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('resource_training.*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->routeIs('resource_training.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-print"></i>
                            <p>
                                Resources Training
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('resource_training.index') }}"
                                    class="nav-link {{ request()->routeIs('resource_training.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('resource_training.create') }}"
                                    class="nav-link {{ request()->routeIs('resource_training.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('general_setting.*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->routeIs('general_setting.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                General Setting
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('general_setting.index') }}"
                                    class="nav-link {{ request()->routeIs('general_setting.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
