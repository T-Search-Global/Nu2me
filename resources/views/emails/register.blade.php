
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registeration Successfully | {{config('app.name')}}</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body style="text-align:center;margin:0; padding:0; font-family:'Poppins', sans-serif !important; background-color:#f9f9ff; text-align: center;">

<!-- Main Container -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f9f9ff">
  <tr>
    <td align="center" valign="top">
      <table width="600" border="0" cellspacing="0" cellpadding="0" style="text-align:center;background-color:#ffffff; margin:20px auto;">

        <!-- Header with Logo -->
        <tr>
          <td align="center" style="text-align:center;padding:40px 20px 20px;">
            <img src="{{ url('assets/images/Aptlogo.png') }}" alt="{{config('app.name')}}" width="150" style="text-align:center;max-width:150px; height:auto;" />
          </td>
        </tr>

        <!-- Title Section -->
        <tr>
          <td align="center">
            <h1 style="text-align:center;color:#fff; font-size:28px; margin:0; font-weight:600; padding:20px; background-color: #67bbe2de;border-radius: 20px 20px 0 0;margin-bottom: 20px;">Registeration Successful | {{config('app.name')}}</h1>
          </td>
        </tr>

        <!-- Content Section -->
        <tr>
          <td align="left" style="text-align:center;padding:0 40px 20px; color:#000; font-size:16px; line-height:1.6;">

            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align:center;margin:30px 0; border-top:1px solid #eaeaea;">
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table>
            <p style="text-align:center;margin:0 0 20px 0;"><strong>Name:</strong> {{ $first_name }} {{ $last_name }}</p>
            <p>Thank You For Registeration</p>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td align="center" style="padding:30px 40px 20px; background-color:#ffffff;">
            <!-- Social Icons -->
            <table border="0" cellspacing="0" cellpadding="0" style="text-align:center;margin:20px 0;">
              <tr>
                <td align="center" style="text-align:center;padding:0 10px;">
                  <a href="" style="text-align:center;display:inline-block;"><img src="https://www.instagram.com/static/images/ico/favicon-192.png/68d99ba29cc8.png" width="24" alt="Instagram" style="text-align:center;display:block;" /></a>
                </td>
                <td align="center" style="text-align:center;padding:0 10px;">
                  <a href="" style="text-align:center;display:inline-block;"><img src="https://www.facebook.com/favicon.ico" width="24" alt="Facebook" style="text-align:center;display:block;" /></a>
                </td>
              </tr>
            </table>

            <!-- Contact Information -->
            <table border="0" cellspacing="0" cellpadding="0" style="text-align:center;margin:20px 0 0 0; width:100%;">
              <tr>
                <td align="center" style="text-align:center;padding:5px 0; font-size:15px; color:#000;">
                  {{-- <strong>Phone:</strong> (575) 635-3066 --}}
                  <p>Thank you for reaching out to {{config('app.name')}}. We will get back to you soon.</p>
                </td>
              </tr>

              <tr>
                <td align="center" style="text-align:center;padding:5px 0; font-size:15px; color:#000;">
                  <strong>Address:</strong> 141 FAKE TT ADDRESS LAS CRUCES,  TT ADDRESS
                </td>
              </tr>
            </table>

            <!-- Copyright -->
            <p style="text-align:center;margin:20px 0 0 0; font-size:12px; color:#000;">© 2025 {{config('app.name')}}. All rights reserved.</p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

</body>
</html>
