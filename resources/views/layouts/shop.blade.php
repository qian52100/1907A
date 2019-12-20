@include('index.public.header')
@yield('content')
     <div class="height1"></div>
@include('index.public.footer')
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{ asset('/static/index/js/jquery.min.js') }}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ asset('/static/index/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/static/index/js/style.js') }}"></script>
    <!--焦点轮换-->
    <script src="{{ asset('/static/index/js/jquery.excoloSlider.js') }}"></script>
    <script>
		$(function () {
		 $("#sliderA").excoloSlider();
		});
	</script>
  </body>
</html>
