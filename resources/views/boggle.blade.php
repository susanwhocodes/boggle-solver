<html>
{{ Html::style('css/app.css') }}
    <body>
        <h1>Enter Your Boggle Board</h1>

		{!! Form::open(['route' => 'boggle.enter']) !!}

		<div class="form-group">
			<table>
        		@for ($i = 0; $i < 4; $i++)
        		<tr>
        			@for ($j = 0; $j < 4; $j++)
        			<td>
        				{!! Form::text("c".$i.$j, null, ['class' => 'form-control']) !!}
        	
        			</td>
        			@endfor
        		</tr>
        		@endfor
        	</table>
		</div>


		{!! Form::submit('Solve it!', ['class' => 'btn btn-info']) !!}

		{!! Form::close() !!}

	@if ($errors->any())
    	<div class="alert alert-danger">
        	<ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        	</ul>
    	</div>
	@endif


	@if($results && $results->count() > 0)
		<p> {{ $results->count() }} words available. Searched for {{ $totalSearched }} potential words.</p>
	@endif
	@forelse ($results as $result)
	    <li>{{ $result }}</li>
	@empty
	@endforelse

    </body>
</html>
