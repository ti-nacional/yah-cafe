@extends('layouts.dashboard')

@section('content')
 <div class="content with-top-banner">
    <div class="row">		
        <div class="col-md-12">
			<div class="not-found-box">
				<div class="error-code">503</div>
				<div class="error-status">Página não encontrada</div>
				<div class="error-text">Whoops! Ocorreu um problema...</div>
				<div class="not-found-footer">
					<form class="form-inline justify-content-center">
						<a href="/" class="btn btn-primary sm-max sm-mgtop-5" type="submit"> Voltar</a>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection