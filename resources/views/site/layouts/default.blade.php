<!DOCTYPE html>

<html class="no-js">

    @include('site/layouts.head')




    	@include('site/layouts.header')
        @yield('content')

   	 	@yield('styles')
    
   		@include('site/layouts.footer')

	</div>
	 	@yield('scripts')
</body>
</html> 