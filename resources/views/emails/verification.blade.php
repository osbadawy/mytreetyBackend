{{-- <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#e8ebef">
    @php
    $logo = get_setting('header_logo');
    @endphp
    <tr>
        <td align="center" valign="top" class="container" style="padding:50px 10px;">
            <!-- Container -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center">
                        <table width="650" border="0" cellspacing="0" cellpadding="0" class="mobile-shell">
                            <tr>
                                <td class="td" bgcolor="#ffffff" style="width:650px; min-width:650px; font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                    <!-- Header -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                                        <tr>
                                            <td class="p30-15-0" style="padding: 40px 30px 0px 30px;">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <th class="column" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td class="img m-center" style="font-size:0pt; line-height:0pt; text-align:left;"><img src="{{ uploaded_asset($logo) }}" width="140"  border="0" alt="" /></td>
                                                                </tr>
                                                            </table>
                                                        </th>
                                                        <th class="column-empty" width="1" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;"></th>
                                                        <th class="column" width="120" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td class="text-header right" style="color:#000000; font-family:'Fira Mono', Arial,sans-serif; font-size:12px; line-height:16px; text-align:right;"><a href="{{ env('APP_URL') }}" target="_blank" class="link" style="color:#000001; text-decoration:none;"><span class="link" style="color:#000001; text-decoration:none;">{{ env('APP_NAME') }}</span></a></td>
                                                                </tr>
                                                            </table>
                                                        </th>
                                                    </tr>
                                                </table>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td class="separator" style="padding-top: 40px; border-bottom:4px solid #000000; font-size:0pt; line-height:0pt;">&nbsp;</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- END Header -->

                                    <!-- Intro -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                                        <tr>
                                            <td class="p30-15" style="padding: 70px 30px 70px 30px;">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td class="h2 center pb10" style="color:#000000; font-family:'Ubuntu', Arial,sans-serif; font-size:50px; line-height:60px; text-align:center; padding-bottom:10px;">{{ $array['subject'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="h5 center blue pb30" style="font-family:'Ubuntu', Arial,sans-serif; font-size:20px; line-height:26px; text-align:center; color:#2e57ae; padding-bottom:30px;">{{ $array['content'] }} </td>
                                                    </tr>
                                                    @if(!empty( $array['link']))
                                                    <tr>
                                                        <td class="h5 center blue pb30" style="font-family:'Ubuntu', Arial,sans-serif; font-size:20px; line-height:26px; text-align:center; color:#2e57ae; padding-bottom:30px;">
                                                            <a href="{{ $array['link'] }}" style="background: #007bff;padding: 0.9rem 2rem;font-size: 0.875rem;color:#fff;border-radius: .2rem;" target="_blank">{{ translate("Click Here") }}</a>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- END Intro -->
                                </td>
                            </tr>
                            <tr>
                                <td class="text-footer" style="padding-top: 30px; color:#1f2125; font-family:'Fira Mono', Arial,sans-serif; font-size:12px; line-height:22px; text-align:center;">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!-- END Container -->
        </td>
    </tr>
</table> --}}


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Email verification - Mytreety</title>
    <style type="text/css">
      body {
        font-family: Montserrat;
        margin: 0;
        padding: 0;
        background-color: #ffff;
      }
      table {
        border-spacing: 0;
        width: 100%;
      }
      td {
        padding: 0;
        vertical-align: middle;
        text-align: center;
      }
      img {
        border: 0;
      }
      li {
        display: inline-block;
        margin: 0 10px;
      }
      /* li img {
        width: 39.33px;
      } */
      .wrapper {
        table-layout: fixed;
        background-color: #ffff;
      }
      .webkit {
        max-width: 600px;
        margin: auto;
        background-color: #ffff;
      }
      .three-underlines > td {
        text-decoration: underline;
      }
      @media screen and (max-width: 600px) {
      }
      @media screen and (max-width: 400px) {
      }
    </style>
  </head>
  <body>
    <center class="wrapper">
      <div class="webkit">
        <table class="outer" align="center">
          <tr>
            <td>
              <table width="100%" align="center">
                <tr>
                  <td>
                    <table width="100%" align="center">
                      <tr>
                        <td>
                          <img
                            src="{{url('/')}}/public/frontend/emails/leaf-a-few-green-leaves-e6a0bee36bbf4099f72a7a4e19c625b9-200x141.png"
                            alt="few leafs"
                          />
                        </td>
                        <td>
                          <img
                            src="{{url('/')}}/public/frontend/emails/leaf-a-few-green-leaves-e6a0bee36bbf4099f72a7a4e19c625b9-200x141.png"
                            alt="few leafs"
                            class="left-leaf"
                          />
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td>
                    <center
                      style="
                        background-color: #ffffff;
                        border-radius: 15px;
                        width: 100%;
                      "
                    >
                      <table width="100%">
                        <tr>
                          <td>
                            <a href="https://www.mytreety.com" target="_blank"
                              ><img
                                src="{{url('/')}}/public/frontend/emails/Mytreety-main-header-logo-150x66.png"
                                alt="Mytreety"
                            /></a>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <p
                              style="
                                font-size: 20px;
                                font-weight: bold;
                                padding: 1px 30px;
                              "
                            >
                              E-Mail-Bestätigung
                            </p>
                            <p
                              style="
                                color: #7bb542;
                                font-size: 18px;
                                font-weight: bold;
                                padding: 1px 30px;
                              "
                            >
                              Bitte klicken Sie unten, um Ihre E-Mail-Adresse zu
                              bestätigen.
                            </p>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <button
                              style="
                                background-color: #7bb542;
                                font-size: 20px;
                                font-weight: 600;
                                color: #ffffff;
                                margin: 4px auto;
                                text-decoration: underline;
                                border: none;
                                padding: 30px;
                                padding-inline: 40px;
                                border-radius: 15px;
                                box-shadow: 4px 4px 6px #b5d884 inset;
                              "
                            >
                              <a href="{{$array['link']}}" style="color: #ffff;">Hier klicken</a>
                            </button>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <ul style="list-style-type: none; padding: 0">
                              <li>
                                <a href="https://www.facebook.com/Mytreety"
                                  ><img
                                    src="{{url('/')}}/public/frontend/emails/facebook-icon.png"
                                    alt="Mytreety on Facebook"
                                /></a>
                              </li>
                              <li>
                                <a href="https://twitter.com/Mytreety"
                                  ><img
                                    src="{{url('/')}}/public/frontend/emails/twitter-icon.png"
                                    alt="Mytreety on Twitter"
                                /></a>
                              </li>
                              <li>
                                <a
                                  href="https://www.linkedin.com/company/mytreety/"
                                  ><img
                                    src="{{url('/')}}/public/frontend/emails/linkedin-icon.png"
                                    alt="Mytreety on Linkedin"
                                /></a>
                              </li>
                              <li>
                                <a href="https://www.instagram.com/mytreety/"
                                  ><img
                                    src="{{url('/')}}/public/frontend/emails/instagram-icon.png"
                                    alt="Mytreety on Instagram"
                                /></a>
                              </li>
                            </ul>
                          </td>
                        </tr>
                      </table>
                    </center>
                  </td>
                </tr>
                <tr>
                  <td>
                    <table width="100%" style="margin: 20px auto">
                        <tr class="three-underlines">
                            <td><a href="{{url('buyer-policy')}}">Käufer Richtlinien</a></td>
                            <td><a href="{{route('terms')}}">AGB</a></td>
                            <td><a href="{{url('/privacypolicy')}}">Datenschutzbestimmungen</a></td>
                          </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="text-align: left; color: #707070">
                    Diese E-Mail ist lediglich eine Bestätigung des Eingangs
                    Ihrer Bestellung. Ihr Vertrag über den Kauf dieser Artikel
                    kommt erst zustande, wenn wir Sie per E-Mail darüber
                    informieren, dass die Artikel versandt wurden (und die
                    entsprechenden Informationen zur Sendungsverfolgung
                    anhängen). Bitte antworten Sie nicht auf diese Nachricht.
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </div>
    </center>
  </body>
</html>
