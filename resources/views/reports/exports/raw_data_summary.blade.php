<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>SUMMARY</title>
  <link rel="stylesheet" href="{{ asset('assets/css/offers_reporting_export.css') }}">
  <style type="text/css">
    td {
      height:20px;
    }
  </style>
</head>
<body>
  <table border="1" cellspacing="0">
    <tbody>
      <tr>
        <td width="25" height="20" style="font-size: 14px;" valign="middle">Raw Data Export</td>
      </tr>
      <tr></tr>
      <tr>
        <td><strong>Report Date:</strong></td>
        <td style="color:#119e11;">{{ \Carbon\Carbon::now()->format('Y-m-d H:ia') }}</td>
      </tr>
      <tr></tr>
      <tr></tr>
      <tr></tr>
      <tr>
        <td><strong>TAB</strong></td>
        <td><strong>TAB HEADERS ON EACH TAB</strong></td>
      </tr>
      @foreach($db_summary as $key => $value)
        <tr>
          <td>{{ $key }}</td>
          @foreach($value as $val)
            <td width="15">{{ $val }}</td>
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>