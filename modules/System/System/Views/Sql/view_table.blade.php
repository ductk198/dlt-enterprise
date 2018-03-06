 <h4>Thông tin bảng: {{$name_table}}</h4>
 <table class="table table-condensed table-striped">
    <thead>
      <tr>
        <th>Name column</th>
        <th>Data type</th>
		<th>Max value</th>
        <th>Allows Null</th>
      </tr>
    </thead>
    <tbody>
	@foreach($tables as $table)
	  <tr>
        <td>{{$table['COLUMN_NAME']}}</td>
        <td>{{$table['DATA_TYPE']}}</td>
        <td>{{$table['CHARACTER_MAXIMUM_LENGTH']}}</td>
		<td>{{$table['IS_NULLABLE']}}</td>
      </tr>	
	@endforeach
    </tbody>
  </table>