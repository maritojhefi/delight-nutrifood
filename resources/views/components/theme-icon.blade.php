@if (isset(auth()->user()->color_page) && auth()->user()->color_page == 'theme-dark')
    <i class="fas fa-sun color"></i>
@elseif(isset(auth()->user()->color_page) && auth()->user()->color_page == 'theme-light')
    <i class="fas fa-moon color"></i>
@else
    <i class="fas fa-moon color"></i>
@endif