 <!-- ======= Sidebar ======= -->
 <aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">
  <li class="nav-item">
    <a class="nav-link {{ request()->is('customers*') && !request()->is('customers/create*') ? '' : 'collapsed' }}" data-bs-target="#customers-nav" data-bs-toggle="collapse" href="#">
      <span>Customers</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="customers-nav" class="nav-content {{ request()->is('customers*') && !request()->is('customers/create*') ? 'show' : 'collapse' }}" data-bs-parent="#sidebar-nav">
      <li>
        <a href="{{ route('customers.index') }}" class="{{ request()->is('customers*') && !request()->is('customers/create*') ? 'active' : '' }}">
          <i class="bi bi-circle"></i><span>Manage Customers</span>
        </a>
      </li>

    </ul>
</li>


  
  <li class="nav-item">
    <a class="nav-link {{ request()->is('POS*') && !request()->is('POS/createOrder*') ? '' : 'collapsed' }}" data-bs-target="#orders-nav" data-bs-toggle="collapse" href="#">
      <span>Orders || POS  @if(Session::has('pending_order_count') && Session::get('pending_order_count') > 0)
        <!-- Display the count -->
        <span class="badge bg-warning ms-2 badge-number">{{ Session::get('pending_order_count') }}</span>
    @endif </span>
      <i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="orders-nav" class="nav-content {{ request()->is('POS*') ? 'show' : 'collapse' }} " data-bs-parent="#sidebar-nav">
      <li>
        <a href="{{ route('POS.index') }}" class="{{ request()->is('POS*') && request()->is('POS/index*') ? 'active' : '' }}">
          <i class="bi bi-circle"></i><span class="d-flex align-items-center">Manage Orders @if(Session::has('pending_order_count') && Session::get('pending_order_count') > 0)
        <!-- Display the count -->
        <span class="badge bg-warning ms-2 badge-number">{{ Session::get('pending_order_count') }}</span>
    @endif</span>
        </a>
      </li>
      <li>
        <a href="{{ route('POS.createOrder') }}" class="{{ request()->is('POS*') && request()->is('POS/createOrder*') ? 'active' : '' }}">
          <i class="bi bi-circle"></i><span>Create Order || POS</span>
        </a>
      </li>
    </ul>
  </li><!-- End Forms Nav -->
  <li class="nav-item">
    <a class="nav-link {{ request()->is('inventory*') && !request()->is('inventory/index*') ? '' : 'collapsed' }}" data-bs-target="#inventory-nav" data-bs-toggle="collapse" href="#">
      <span>Inventory
    @if(Session::has('low_quantity_products_count') && Session::get('low_quantity_products_count') > 0)
        <!-- Display the count -->
        <span class="badge bg-danger ms-2 badge-number">{{ Session::get('low_quantity_products_count') }}</span>
    @endif
</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="inventory-nav" class="nav-content {{ request()->is('inventory*') && !request()->is('inventory/index*') ? 'show' : 'collapse' }} " data-bs-parent="#sidebar-nav">
      <li>
        <a href="{{ route('inventory.index') }}" class="{{ request()->is('inventory*') ? 'active' : '' }}">
          <i class="bi bi-circle"></i><span class="d-flex align-items-center">Manage Inventory
    @if(Session::has('low_quantity_products_count') && Session::get('low_quantity_products_count') > 0)
        <!-- Display the count -->
        <span class="badge bg-danger ms-2 badge-number">{{ Session::get('low_quantity_products_count') }}</span>
    @endif
</span>
        </a>
      </li>
    </ul>
  </li><!-- End Forms Nav -->
  <li class="nav-item">
    <a class="nav-link {{ request()->is('invoice*') && !request()->is('invoice/index*') ? '' : 'collapsed' }}" data-bs-target="#invoice-nav" data-bs-toggle="collapse" href="#">
      <span>Invoice</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="invoice-nav" class="nav-content {{ request()->is('invoice*') && !request()->is('invoice/invoiceTemplate*') ? 'show' : 'collapse' }} " " data-bs-parent="#sidebar-nav">
      <li>
        <a href="{{ route('invoice.invoiceTemp') }}" class="{{ request()->is('invoice*') && !request()->is('invoice/index*') ? 'active' : '' }}">
          <i class="bi bi-circle"></i><span>Invoice Settings</span>
        </a>
      </li>
    </ul>
  </li><!-- End Forms Nav -->
  <li class="nav-item">
    <a class="nav-link {{ request()->is('billing*') ? '' : 'collapsed' }}" data-bs-target="#billing-nav" data-bs-toggle="collapse" href="#">
      <span>Billing</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="billing-nav" class="nav-content {{ request()->is('billing*') ? 'show' : 'collapse' }} "  " data-bs-parent="#sidebar-nav">
      <li>
        <a href="{{ route('Billing.manageBills') }}" class="{{request()->is('billing.manageBills*') ? 'active' : '' }}">
          <i class="bi bi-circle"></i><span>Manage Billing</span>
        </a>
      </li>
      <li>
        <a href="{{ route('Billing.index') }}" class="{{ request()->is('billing*') && !request()->is('billing.manageBills*') ? 'active' : '' }}">
          <i class="bi bi-circle"></i><span>Create Bills</span>
        </a>
      </li>
    </ul>
  </li><!-- End Forms Nav -->
</ul>

</aside><!-- End Sidebar-->