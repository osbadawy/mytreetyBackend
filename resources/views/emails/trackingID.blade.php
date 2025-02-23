<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tracking ID - Mytreety</title>
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
                    <table>
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
                            <table>
                              <tr>
                                <td>
                                  <a
                                    href="https://www.mytreety.com"
                                    target="_blank"
                                    ><img
                                      src="{{url('/')}}/public/frontend/emails/Mytreety-main-header-logo-150x66.png"
                                      alt="Mytreety"
                                  /></a>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <p style="font-size: 24px; font-weight: bold">
                              Ihr Produkt wurde versandt!
                            </p>
                            <p style="font-size: 24px; font-weight: bold">
                              Logistikträger: {{$details['tracking_code']}}
                            </p>
                            <p style="font-size: 24px; font-weight: bold">
                              Verfolgungs-ID: {{$details['tracking_carrier']}}
                            </p>
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
                    <button
                      style="
                        background-color: #7bb542;
                        font-size: 20px;
                        font-weight: 600;
                        color: #ffffff;
                        margin: 40px auto;
                        text-decoration: underline;
                        border: none;
                        padding: 20px;
                        padding-inline: 40px;
                        border-radius: 15px;
                        box-shadow: 4px 4px 6px #b5d884 inset;
                      "
                    >
                    Jetzt einkaufen
                  </button>
                  </td>
                </tr>
                <tr >
                  <td>
                    <table width="100%">
                      <tr>
                        <td>
                          <table style="margin: auto auto 20px auto;">
                            <tr class="three-underlines">
                                <td><a href="{{url('buyer-policy')}}">Käufer Richtlinien</a></td>
                                <td><a href="{{route('terms')}}">AGB</a></td>
                                <td><a href="{{url('/privacypolicy')}}">Datenschutzbestimmungen</a></td>
                              </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="text-align: left; color:#707070; padding-bottom: 8px">
                    Diese E-Mail ist lediglich eine Bestätigung des Eingangs
                    Ihrer Bestellung. Ihr Vertrag über den Kauf dieser Artikel
                    kommt erst zustande, wenn wir Sie per E-Mail darüber
                    informieren, dass die Artikel versandt wurden (und die
                    entsprechenden Informationen zur Sendungsverfolgung
                    anhängen). Bitte antworten Sie nicht auf diese Nachricht.
                  </td>
                </tr>
        </table>
      </div>
    </center>
  </body>
</html>
