<ul class="nav flex-column mt-3">

    <li class="nav-item">
        <a href="/dashboard" class="nav-link text-white">Dashboard</a>
    </li>

    <li class="nav-item">
        <a href="/orders-ui" class="nav-link text-white">Orders</a>
    </li>

    <li class="nav-item">
        <a href="/menu-ui" class="nav-link text-white">Menu</a>
    </li>

    <li class="nav-item">
        <a href="/category-ui" class="nav-link text-white">Categories</a>
    </li>

    <li>
        <form method="POST" action="/logout">
        @csrf
        <button class="btn btn-danger">Logout</button>
    </form>
    </li>

</ul>