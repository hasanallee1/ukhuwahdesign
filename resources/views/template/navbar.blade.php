 <!-- Navbar -->
 <nav class="main-header navbar navbar-expand navbar-white navbar-light">
     <!-- Left navbar links -->
     <ul class="navbar-nav">
         <li class="nav-item">
             <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
         </li>
     </ul>

     <!-- Right navbar links -->
     <ul class="navbar-nav ml-auto">
         <li class="nav-item">
             <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                 <i class="fas fa-expand-arrows-alt"></i>
             </a>
         </li>

         <li class="nav-item dropdown">
             <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                 aria-expanded="false">
                 {{ Auth::user()->name }}
             </a>
             <div class="dropdown-menu">
                 <a class="dropdown-item" href="/admin/user/showProfile/{{ Auth::user()->id }}">My Profile</a>
                 <div class="dropdown-divider"></div>
                 <form action="{{ route('logout') }}" method="POST">
                     @csrf
                     <button type="submit" class="dropdown-item">Logout
                     </button>
                 </form>
             </div>
         </li>





     </ul>
 </nav>
 <!-- /.navbar -->
