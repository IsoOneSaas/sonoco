<!-- resources/views/layouts/icewall.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="{{ url('assets/images/favicon.ico') }}" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="MPR Consulting">
        @stack('meta')
        <title>ISO-ONE :: {{ $title ?? 'Gestión Total de la Calidad' }}</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="{{ url('assets/css/app.css') }}" />
        <!-- END: CSS Assets-->
        <!-- BEGIN: Custom CSS-->
        @stack('styles')
        <!-- END: Custom CSS-->
        <!-- BEGIN: Custom Scripts -->
        @stack('scripts-top') 
        <!-- END: Custom Scripts -->
    </head>
    <!-- END: Head -->

    <body class="main">
        <!-- BEGIN: Mobile Menu -->
        <div class="mobile-menu md:hidden">
            <div class="mobile-menu-bar">
                <a href="" class="flex mr-auto">
                    <img alt="Logo iso-one" class="h-12" src="{{ url('/assets/images/logo.jpg') }}">
                </a>
                <a href="javascript:;" class="mobile-menu-toggler"> <i data-lucide="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i> </a>
            </div>
            <div class="scrollable">
                <a href="javascript:;" class="mobile-menu-toggler"> <i data-lucide="x-circle" class="w-8 h-8 text-white transform -rotate-90"></i> </a>
                <ul class="scrollable__content py-2">
                    <li>
                        <a href="javascript:;.html" class="side-menu menu--active">
                            <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                            <div class="side-menu__title"> Dashboard <i data-lucide="chevron-down" class="side-menu__sub-icon transform rotate-180"></i> </div>
                        </a>
                        <ul class="side-menu__sub-open">
                            <li>
                                <a href="side-menu-light-dashboard-overview-1.html" class="side-menu">
                                    <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="side-menu__title"> Webmaster </div>
                                </a>
                            </li>
                            <li>
                                <a href="index.html" class="side-menu menu--active">
                                    <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="side-menu__title"> Administrador </div>
                                </a>
                            </li>
                            <li>
                                <a href="side-menu-light-dashboard-overview-3.html" class="side-menu">
                                    <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="side-menu__title"> Usuario </div>
                                </a>
                            </li>
                            <li>
                                <a href="side-menu-light-dashboard-overview-4.html" class="side-menu">
                                    <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="side-menu__title"> Invitado </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;" class="side-menu">
                            <div class="side-menu__icon"> <i data-lucide="box"></i> </div>
                            <div class="side-menu__title"> Documentos <i data-lucide="chevron-down" class="side-menu__sub-icon "></i> </div>
                        </a>
                        <ul class="">

                            <li>
                                <a href="javascript:;" class="side-menu">
                                    <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="side-menu__title"> Ajustes <i data-lucide="chevron-down" class="side-menu__sub-icon "></i> </div>
                                </a>
                                <ul class="">
                                    <li>
                                        <a href="side-menu-light-modal.html" class="side-menu">
                                            <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                            <div class="side-menu__title">Personalización</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="side-menu-light-slide-over.html" class="side-menu">
                                            <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                            <div class="side-menu__title">Tipos de documentos</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="side-menu-light-notification.html" class="side-menu">
                                            <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                            <div class="side-menu__title">Permisos</div>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript:;" class="side-menu">
                                    <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="side-menu__title"> Administrar <i data-lucide="chevron-down" class="side-menu__sub-icon "></i> </div>
                                </a>
                                <ul class="">
                                    <li>
                                        <a href="side-menu-light-modal.html" class="side-menu">
                                            <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                            <div class="side-menu__title">Procesamiento</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="side-menu-light-slide-over.html" class="side-menu">
                                            <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                            <div class="side-menu__title">Mantenimiento</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="side-menu-light-notification.html" class="side-menu">
                                            <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                            <div class="side-menu__title">Reportes</div>
                                        </a>
                                    </li>
                                </ul>
                            </li>                            

                        </ul>
                    </li>


                    <li class="side-menu__devider my-6"></li>


                </ul>
            </div>
        </div>
        <!-- END: Mobile Menu -->
        <!-- BEGIN: Top Bar -->
        <div class="top-bar-boxed h-[70px] z-[51] relative border-b border-white/[0.08] mt-12 md:-mt-5 -mx-3 sm:-mx-8 px-3 sm:px-8 md:pt-0 mb-12">
            <div class="h-full flex items-center">
                <!-- BEGIN: Logo -->
                <a href="" class="-intro-x hidden md:flex">
                    <img alt="Logo Inquilino" src="{{ url('tenants/sonoco/images/logo.png') }}" class="h-12"> 
                </a>
                <!-- END: Logo -->
                <!-- BEGIN: Breadcrumb -->
                <nav aria-label="breadcrumb" class="-intro-x h-full mr-auto">                    
                    <ol class="breadcrumb breadcrumb-light">
                        {{ $breadcrumb ?? '' }}
                    </ol>
                </nav>
                <!-- END: Breadcrumb -->
                <!-- BEGIN: Search -->
                <div class="intro-x relative mr-3 sm:mr-6">

                    <a class="notification notification--light sm:hidden" href=""> <i data-lucide="search" class="notification__icon dark:text-slate-500"></i> </a>
                    <div class="search-result">
                        <div class="search-result__content">
                            <div class="search-result__content__title">Pages</div>
                            <div class="mb-5">
                                <a href="" class="flex items-center">
                                    <div class="w-8 h-8 bg-success/20 dark:bg-success/10 text-success flex items-center justify-center rounded-full"> <i class="w-4 h-4" data-lucide="inbox"></i> </div>
                                    <div class="ml-3">Mail Settings</div>
                                </a>
                                <a href="" class="flex items-center mt-2">
                                    <div class="w-8 h-8 bg-pending/10 text-pending flex items-center justify-center rounded-full"> <i class="w-4 h-4" data-lucide="users"></i> </div>
                                    <div class="ml-3">Users & Permissions</div>
                                </a>
                                <a href="" class="flex items-center mt-2">
                                    <div class="w-8 h-8 bg-primary/10 dark:bg-primary/20 text-primary/80 flex items-center justify-center rounded-full"> <i class="w-4 h-4" data-lucide="credit-card"></i> </div>
                                    <div class="ml-3">Transactions Report</div>
                                </a>
                            </div>
                            <div class="search-result__content__title">Users</div>
                            <div class="mb-5">
                                <a href="" class="flex items-center mt-2">
                                    <div class="w-8 h-8 image-fit">
                                        <img alt="Midone - HTML Admin Template" class="rounded-full" src="{{ url('assets/images/profile-1.jpg') }}">
                                    </div>
                                    <div class="ml-3">Russell Crowe</div>
                                    <div class="ml-auto w-48 truncate text-slate-500 text-xs text-right">russellcrowe@left4code.com</div>
                                </a>
                                <a href="" class="flex items-center mt-2">
                                    <div class="w-8 h-8 image-fit">
                                        <img alt="Midone - HTML Admin Template" class="rounded-full" src="{{ url('assets/images/profile-11.jpg') }}">
                                    </div>
                                    <div class="ml-3">Denzel Washington</div>
                                    <div class="ml-auto w-48 truncate text-slate-500 text-xs text-right">denzelwashington@left4code.com</div>
                                </a>
                                <a href="" class="flex items-center mt-2">
                                    <div class="w-8 h-8 image-fit">
                                        <img alt="Midone - HTML Admin Template" class="rounded-full" src="{{ url('assets/images/profile-3.jpg') }}">
                                    </div>
                                    <div class="ml-3">Arnold Schwarzenegger</div>
                                    <div class="ml-auto w-48 truncate text-slate-500 text-xs text-right">arnoldschwarzenegger@left4code.com</div>
                                </a>
                                <a href="" class="flex items-center mt-2">
                                    <div class="w-8 h-8 image-fit">
                                        <img alt="Midone - HTML Admin Template" class="rounded-full" src="{{ url('assets/images/profile-11.jpg') }}">
                                    </div>
                                    <div class="ml-3">Johnny Depp</div>
                                    <div class="ml-auto w-48 truncate text-slate-500 text-xs text-right">johnnydepp@left4code.com</div>
                                </a>
                            </div>
                            <div class="search-result__content__title">Products</div>
                            <a href="" class="flex items-center mt-2">
                                <div class="w-8 h-8 image-fit">
                                    <img alt="Midone - HTML Admin Template" class="rounded-full" src="{{ url('assets/images/preview-14.jpg') }}">
                                </div>
                                <div class="ml-3">Samsung Galaxy S20 Ultra</div>
                                <div class="ml-auto w-48 truncate text-slate-500 text-xs text-right">Smartphone &amp; Tablet</div>
                            </a>
                            <a href="" class="flex items-center mt-2">
                                <div class="w-8 h-8 image-fit">
                                    <img alt="Midone - HTML Admin Template" class="rounded-full" src="{{ url('assets/images/preview-8.jpg') }}">
                                </div>
                                <div class="ml-3">Sony A7 III</div>
                                <div class="ml-auto w-48 truncate text-slate-500 text-xs text-right">Photography</div>
                            </a>
                            <a href="" class="flex items-center mt-2">
                                <div class="w-8 h-8 image-fit">
                                    <img alt="Midone - HTML Admin Template" class="rounded-full" src="{{ url('assets/images/preview-5.jpg') }}">
                                </div>
                                <div class="ml-3">Nike Tanjun</div>
                                <div class="ml-auto w-48 truncate text-slate-500 text-xs text-right">Sport &amp; Outdoor</div>
                            </a>
                            <a href="" class="flex items-center mt-2">
                                <div class="w-8 h-8 image-fit">
                                    <img alt="Midone - HTML Admin Template" class="rounded-full" src="{{ url('assets/images/preview-8.jpg') }}">
                                </div>
                                <div class="ml-3">Samsung Galaxy S20 Ultra</div>
                                <div class="ml-auto w-48 truncate text-slate-500 text-xs text-right">Smartphone &amp; Tablet</div>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- END: Search -->

                @if( in_array(Auth::user()->role, config('settings.roles_admin')) )
                    @can('setup_parameters')
                    <!-- BEGIN: Setting Menu -->
                    <div class="intro-x dropdown mr-4 sm:mr-6">
                    <div class="dropdown-toggle notification cursor-pointer" role="button" aria-expanded="false" data-tw-toggle="dropdown"> <i data-lucide="settings" class=""></i> </div>
                        <div class="dropdown-menu w-56">
                            <ul class="dropdown-content bg-primary/80 before:block before:absolute before:bg-black before:inset-0 before:rounded-md before:z-[-1] text-white">
                                @can('setup_admins')
                                <li>
                                    <a href="{{ route('localizaciones.index') }}" class="dropdown-item hover:bg-white/5"> <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i> Localizaciones </a>
                                </li>
                                <li>
                                    <a href="{{ route('requisitos.index') }}" class="dropdown-item hover:bg-white/5"> <i data-lucide="flag" class="w-4 h-4 mr-2"></i> Requisitos </a>
                                </li>                             
                                <li>
                                    <a href="{{ route('administradores.index') }}" class="dropdown-item hover:bg-white/5"> <i data-lucide="wrench" class="w-4 h-4 mr-2"></i> Administradores </a>
                                </li>                            
                                <li>
                                    <hr class="dropdown-divider border-white/[0.08]">
                                </li>                            
                                @endcan
                                <li>
                                    <a href="{{ route('departamentos.index') }}" class="dropdown-item hover:bg-white/5"> <i data-lucide="at-sign" class="w-4 h-4 mr-2"></i> Departamentos </a>
                                </li>
                                <li>
                                    <a href="{{ route('cargos.index') }}" class="dropdown-item hover:bg-white/5"> <i data-lucide="share2" class="w-4 h-4 mr-2"></i> Cargos </a>
                                </li>
                                <li>
                                    <a href="{{ route('usuarios.index') }}" class="dropdown-item hover:bg-white/5"> <i data-lucide="users" class="w-4 h-4 mr-2"></i> Usuarios </a>
                                </li>
                            
                                <li>
                                    <a href="{{ route('procesos.index') }}" class="dropdown-item hover:bg-white/5"> <i data-lucide="compass" class="w-4 h-4 mr-2"></i> Procesos </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <!-- END: Setting Menu -->                
                    @endcan
                @endif

                <!-- BEGIN: Account Menu -->
                <div class="intro-x dropdown w-8 h-8">
                    <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in scale-110" role="button" aria-expanded="false" data-tw-toggle="dropdown">
                        <img alt="Avatar Usuario" src="{{ url('tenants/sonoco/images/avatar_'. auth()->user()->user_uid .'.jpg') ?? url('assets/images/avatar_blank.png') }}">
                    </div>
                    <div class="dropdown-menu w-56">
                        <ul class="dropdown-content bg-primary/80 before:block before:absolute before:bg-black before:inset-0 before:rounded-md before:z-[-1] text-white">
                            <li class="p-2">
                                <div class="font-medium">{{auth()->user()->name}}</div>
                                <div class="text-xs text-white/60 mt-0.5 dark:text-slate-500">{{ ucfirst(config('settings.roles.'. auth()->user()->role .'')) }}</div>
                            </li>
                            <li>
                                <hr class="dropdown-divider border-white/[0.08]">
                            </li>
                            <li>
                                <a href="{{ route('perfil.index') }}" class="dropdown-item hover:bg-white/5"> <i data-lucide="user" class="w-4 h-4 mr-2"></i> Perfil </a>
                            </li>
                            <li>
                                <a href="https://iso-one.com/soporte/open.php?e={{ Auth::user()->email }}" target="_blank" class="dropdown-item hover:bg-white/5"> <i data-lucide="life-buoy" class="w-4 h-4 mr-2"></i> Soporte </a>
                            </li>                            
                            <li>
                                <hr class="dropdown-divider border-white/[0.08]">
                            </li>
                            <li>
                                <a href="javascript:;" onClick="document.getElementById('logout-form').submit()" class="dropdown-item hover:bg-white/5"> <i data-lucide="power" class="w-4 h-4 mr-2"></i> Salir </a>
                                <form id="logout-form" action="{{ url('logout') }}" method="POST">
                                    {{ csrf_field() }}
                                </form>                                
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- END: Account Menu -->
            </div>
        </div>
        <!-- END: Top Bar -->
        <div class="wrapper">
            <div class="wrapper-box">
                <!-- BEGIN: Side Menu -->
                <nav class="side-nav">
                    <ul>
                        <li>
                            <a href="{{ url('home') }}" class="side-menu @if( request()->route()->getName() == 'home' ) side-menu--active @endif">
                                <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                                <div class="side-menu__title"> Home </div>
                            </a>
                        </li>                         
                        <li>
                            <a href="{{ route('documents.dashboard') }}" class="side-menu @if( request()->route()->getName() == 'documents.dashboard' ) side-menu--active @endif">
                                <div class="side-menu__icon"> <i data-lucide="gauge"></i> </div>
                                <div class="side-menu__title"> Dashboard </div>
                            </a>
                        </li>                    
                        <li>
                            <a href="javascript:;" class="side-menu @if( Str::contains( request()->route()->getName(), 'documents.') ) side-menu--active @endif ">
                                <div class="side-menu__icon"> <i data-lucide="files"></i> </div>
                                <div class="side-menu__title"> Documentos <i data-lucide="chevron-down" class="side-menu__sub-icon "></i> </div>
                            </a>                            
                            <ul class=" @if( Str::contains( request()->route()->getName(), 'documents.') ) side-menu__sub-open @endif ">
                                <!-- <li>
                                    <a href="{{ route('documents.dashboard') }}" class="side-menu @if( request()->route()->getName() == 'documents.dashboard') side-menu--active @endif ">
                                        <div class="side-menu__icon"> <i data-lucide="trello"></i> </div>
                                        <div class="side-menu__title"> Dashboard </div>
                                    </a>
                                </li> -->
                                <li>
                                    <a href="{{ route('documents.master.index') }}" class="side-menu @if( request()->route()->getName() == 'documents.master') side-menu--active @endif ">
                                        
                                        <div class="side-menu__icon"> <i data-lucide="list-ordered"></i> </div>
                                        <div class="side-menu__title"> Listado Maestro </div>
                                    </a>
                                </li>
                                <!--                                 <li>
                                                                    <a href="javascript:;" class="side-menu @if( request()->route()->getName() == 'documents.records') side-menu--active @endif ">
                                                                        <div class="side-menu__icon"> <i data-lucide="book"></i> </div>
                                                                        <div class="side-menu__title"> Registros </div>
                                                                    </a>
                                                                </li>  -->                                                                                                                            
                                <li>
                                    <a href="javascript:;" class="side-menu @if( Str::contains( request()->route()->getName(), 'documents.control') ) side-menu--active @endif">
                                        <div class="side-menu__icon"> <i data-lucide="file-input"></i> </div>
                                        <div class="side-menu__title"> Procesamiento <i data-lucide="chevron-down" class="side-menu__sub-icon "></i> </div>
                                    </a>
                                    <ul class=" @if( Str::contains( request()->route()->getName(), 'documents.control') ) side-menu__sub-open @endif ">
                                        @if( in_array(Auth::user()->role, config('settings.roles_admin')) )
                                            @can('setup_parameters')
                                            <li>
                                                <a href="{{ route('documents.control.documento.index') }}" class="side-menu  @if( Str::contains( request()->route()->getName(), 'documents.control.documento') ) side-menu--active @endif ">
                                                    
                                                    <div class="side-menu__icon"> <i data-lucide="layout-list"></i> </div>
                                                    <div class="side-menu__title">Administrar</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('documents.control.solicitud.index') }}" class="side-menu  @if( Str::contains( request()->route()->getName(), 'documents.control.solicitud') ) side-menu--active @endif ">
                                                    <div class="side-menu__icon"> <i data-lucide="file-plus-2"></i> </div>
                                                    <div class="side-menu__title">Solicitudes</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('documents.control.observacion.index') }}" class="side-menu  @if( Str::contains( request()->route()->getName(), 'documents.control.observacion') ) side-menu--active @endif ">
                                                    <div class="side-menu__icon"> <i data-lucide="eye"></i> </div>
                                                    <div class="side-menu__title">Observaciones</div>
                                                </a>
                                            </li>  
                                            <li>
                                                <a href="{{ route('documents.control.seguimiento.index') }}" class="side-menu  @if( Str::contains( request()->route()->getName(), 'documents.control.seguimiento') ) side-menu--active @endif ">
                                                    <div class="side-menu__icon"> <i data-lucide="bell"></i> </div>
                                                    <div class="side-menu__title">Gestión</div>
                                                </a>
                                            </li>                                                                                                                            
                                            @endcan 
                                        @endif                                                                              
                                        <li>
                                            <a href="{{ route('documents.control.manage.index', ['slug' => 'edit']) }}" class="side-menu @if( Str::contains( url()->current(), 'gestion/edit ') ) side-menu--active @endif ">
                                                <div class="side-menu__icon"> <i data-lucide="file-code"></i> </div>
                                                <div class="side-menu__title">Edición @if( $count = App::make("App\Http\Controllers\Document\DashboardController")->setControlBadge('edit')  )<span class="side-menu__sub-icon px-2 mr-2 text-xs text-white rounded-full bg-danger">{{ $count ?? 0 }}</span> @endif </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('documents.control.manage.index', ['slug' => 'review']) }}" class="side-menu @if( Str::contains( url()->current(), 'gestion/review') ) side-menu--active @endif ">
                                                <div class="side-menu__icon"> <i data-lucide="file-search"></i></div>
                                                <div class="side-menu__title">Revisión @if( $count = App::make("App\Http\Controllers\Document\DashboardController")->setControlBadge('review')  )<span class="side-menu__sub-icon px-2 mr-2 text-xs text-white rounded-full bg-danger">{{ $count ?? 0 }}</span> @endif </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('documents.control.manage.index', ['slug' => 'approve']) }}" class="side-menu @if( Str::contains( url()->current(), 'gestion/approve') ) side-menu--active @endif ">
                                                <div class="side-menu__icon"> <i data-lucide="file-check-2"></i> </div>
                                                <div class="side-menu__title">Aprobación @if( $count = App::make("App\Http\Controllers\Document\DashboardController")->setControlBadge('approve')  )<span class="side-menu__sub-icon px-2 mr-2 text-xs text-white rounded-full bg-danger">{{ $count ?? 0 }}</span> @endif </div>
                                            </a>
                                        </li>                                                                                                                         
                                    </ul> 
                                </li>
                                @if( in_array(Auth::user()->role, config('settings.roles_admin')) )
                                    @can('setup_parameters')                                
                                    <li>
                                        <a href="javascript:;" class="side-menu @if( Str::contains( request()->route()->getName(), 'documents.settings') ) side-menu--active @endif ">
                                            <div class="side-menu__icon"> <i data-lucide="settings-2"></i> </div>
                                            <div class="side-menu__title"> Ajustes <i data-lucide="chevron-down" class="side-menu__sub-icon "></i> </div>
                                        </a>
                                        <ul class=" @if( Str::contains( request()->route()->getName(), 'documents.settings') ) side-menu__sub-open @endif ">
                                            <li>
                                                <a href="{{ route('documents.settings.personalizar.index') }}" class="side-menu @if( Str::contains( request()->route()->getName(), 'documents.settings.personalizar') ) side-menu--active @endif">
                                                    <div class="side-menu__icon"> <i data-lucide="wrench"></i> </div>
                                                    <div class="side-menu__title">Personalización</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('documents.settings.plantillas.index') }}" class="side-menu @if( Str::contains( request()->route()->getName(), 'documents.settings.plantillas') ) side-menu--active @endif">
                                                    <div class="side-menu__icon"> <i data-lucide="clipboard"></i> </div>
                                                    <div class="side-menu__title">Plantillas</div>
                                                </a>
                                            </li>                                         
                                            <li>
                                                <a href="{{ route('documents.settings.tipos.index') }}" class="side-menu @if( Str::contains( request()->route()->getName(), 'documents.settings.tipos') ) side-menu--active @endif ">
                                                    <div class="side-menu__icon"> <i data-lucide="type"></i> </div>
                                                    <div class="side-menu__title">Tipos de documento</div>
                                                </a>
                                            </li>                                                                                    
                                            <li>
                                            <a href="{{ route('documents.settings.validez.index') }}" class="side-menu @if( Str::contains( request()->route()->getName(), 'documents.settings.validez') ) side-menu--active @endif ">
                                                    <div class="side-menu__icon"> <i data-lucide="calendar"></i> </div>
                                                    <div class="side-menu__title">Validez</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('documents.settings.autorizaciones.index') }}" class="side-menu">
                                                    <div class="side-menu__icon"> <i data-lucide="lock"></i> </div>
                                                    <div class="side-menu__title">Autorizaciones</div>
                                                </a>
                                            </li>
                                        </ul>                                                                    
                                    </li>
    <!--                                 <li>
                                        <a href="javascript:;" class="side-menu">
                                            <div class="side-menu__icon"> <i data-lucide="crosshair"></i> </div>
                                            <div class="side-menu__title"> Administrar<i data-lucide="chevron-down" class="side-menu__sub-icon "></i> </div>
                                        </a>
                                        <ul class="">
                                            <li>
                                                <a href="javascript:;" class="side-menu">
                                                    <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                                    <div class="side-menu__title">Mantenimiento</div>
                                                </a>
                                            </li>
                                            <li>                                            
                                                <a href="javascript:;" class="side-menu">
                                                    <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                                    <div class="side-menu__title">Reportes</div>
                                                </a>                                            
                                            </li>
                                        </ul>                                    
                                    </li> -->
                                    @endcan
                                @endif
                            </ul>
                        </li>

                        <li>
                            <a href="javascript:;" class="side-menu @if( Str::contains( request()->route()->getName(), 'records.') ) side-menu--active @endif ">
                                <div class="side-menu__icon"> <i data-lucide="library"></i> </div>
                                <div class="side-menu__title"> Registros <i data-lucide="chevron-down" class="side-menu__sub-icon "></i> </div>
                            </a>                            
                            <ul class=" @if( Str::contains( request()->route()->getName(), 'records.') ) side-menu__sub-open @endif ">
                                <li>
                                    <a href="{{ route('records.index') }}" class="side-menu @if( request()->route()->getName() == 'documents.master') side-menu--active @endif ">
                                        
                                        <div class="side-menu__icon"> <i data-lucide="table"></i> </div>
                                        <div class="side-menu__title"> Listado Maestro </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('files.admin.index') }}" class="side-menu @if( request()->route()->getName() == 'files') side-menu--active @endif ">
                                        
                                        <div class="side-menu__icon"> <i data-lucide="archive"></i> </div>
                                        <div class="side-menu__title"> Archivo </div>
                                    </a>
                                </li>
                                
                                
                                 <li>
                                    <a href="javascript:;" class="side-menu @if( Str::contains( request()->route()->getName(), 'files.settings') ) side-menu--active @endif ">
                                        <div class="side-menu__icon"> <i data-lucide="settings-2"></i> </div>
                                        <div class="side-menu__title"> Ajustes <i data-lucide="chevron-down" class="side-menu__sub-icon "></i> </div>
                                    </a>
                                    <ul class=" @if( Str::contains( request()->route()->getName(), 'documents.settings') ) side-menu__sub-open @endif ">
                                        <li>
                                            <a href="{{ route('files.settings.responsibles.index') }}" class="side-menu @if( Str::contains( request()->route()->getName(), 'files.settings.responsibles') ) side-menu--active @endif">
                                                <div class="side-menu__icon"> <i data-lucide="wrench"></i> </div>
                                                <div class="side-menu__title">Responsables</div>
                                            </a>
                                        </li>
                                    </ul>
                                 </li>

                            </ul>
                        </li>                        

                        <li class="side-nav__devider my-6"></li>
                    </ul>
                </nav>
                <!-- END: Side Menu -->
                <div id="basic-non-sticky-notification-content" class="toastify-content hidden flex flex-col sm:flex-row"><div id="notification-message" class="font-medium">Yay! Updates Published!</div></div>
                <button id="basic-non-sticky-notification-toggle" class="" style="display:none">X</button>
                <div id="success-notification-content" class="toastify-content hidden flex"><i id="success-message-icon" class="text-success" data-lucide="check-circle"></i> <div class="ml-4 mr-4"><div id="success-message-1" class="font-medium">Message Saved!</div><div id="success-message-2" class="text-slate-500 mt-1">The message will be sent in 5 minutes.</div></div></div>
                <button id="success-notification-toggle" class="" style="display:none">X</button>

                <!-- BEGIN: Content -->
                {{ $slot }}
                <!-- END: Content -->
            </div>

            <!-- BEGIN: ISO-ONE Content -->

            <!-- END: ISO-ONE Content -->
        </div>
        
        <!-- BEGIN: JS Assets-->
        <script src="{{ url('assets/js/app.js') }}"></script>        
        <script src="{{ url('assets/js/iso.js') }}"></script>
        <script src="{{ url('assets/js/jquery/jquery-3.6.4.min.js') }}"></script>
        <!-- Page level custom scripts -->
        @stack('scripts-bottom')       


        <!-- END: JS Assets-->
    </body>
</html>