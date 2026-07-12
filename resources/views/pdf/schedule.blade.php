<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        @page {
            size: auto;
            margin-top: 0px;
            margin-bottom: 0px;
            margin-left: 0px;
            margin-right: 0px;
            odd-header-name: _blank;
            even-header-name: _blank;
            odd-footer-name: _blank;
            even-footer-name: _blank;
            font-family: 'sans-serif';
        }

        @page havfoter {
            odd-header-name: _blank;
            even-header-name: _blank;
            odd-footer-name: Footer;
            even-footer-name: Footer;
            margin-top: 0px;
            margin-bottom: 0px;
            margin-left: 0px;
            margin-right: 0px;
        }

        @page havfoterhed {
            header: html_schedule_header;
            odd-footer-name: Footer;
            even-footer-name: Footer;
            margin-top: 70mm;
            margin-bottom: 20mm;
            margin-left: 10mm;
            margin-right: 10mm;
        }

        div.havfoter {
            page-break-before: always;
            page: havfoter;
            background-color: #fff;
        }

        div.havfoterhed {
            page-break-before: always;
            page: havfoterhed;
        }

        .introd {
            margin-top: 0;
            padding-top: 100px;
            margin-left: 90px;
            font-size: 20px;
            text-transform: uppercase;
            color: #b49057;
        }

        .blw_hdre {
            color: #fff;
            font-size: 42px;
            padding-top: 10px;
            margin-left: 90px;
            width: 380px;
            line-height: 48px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .page2-introd {
            margin-top: 0;
            padding-top: 70px;
            padding-bottom: 30px;
            margin-left: 30px;
            font-size: 16px;
            color: #b49058;
            text-transform: uppercase;
        }

        .page2-blw_hdre {
            color: #464648;
            font-size: 44px;
            margin-top: 0px;
            padding-top: 0;
            margin-bottom: 40px;
            margin-left: 40px;
            width: 400px;
            line-height: 50px;
            text-transform: uppercase;
            font-weight: normal;
        }

        .bld_cont {
            font-size: 22px;
            line-height: 32px;
            padding-left: 40px;
            padding-right: 40px;
            color: #393d3d;
            text-align: justify;
        }

        .bld_cont_ag {
            font-size: 14px;
            line-height: 22px;
            padding-left: 40px;
            color: #393d3d;
            text-align: justify;
            margin-bottom: 10px;
        }

        .outlin_lft_dv {
            float: left;
            width: 46%;
        }

        .outlin_rit_dv {
            float: right;
            width: 46%;
            padding-right: 40px;
        }

        table.schedule-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 12px;
            margin: 0 auto;
        }

        table.schedule-table th,
        table.schedule-table td {
            padding: 12px 10px;
            font-size: 13px;
            vertical-align: top;
            color: #333;
            border: 1px solid #dfdfdf;
        }

        table.schedule-table thead th {
            background: #f5f5f5;
            font-weight: bold;
            text-align: left;
        }

        .header-container {
            margin: 20px 0 0 10px;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- FOOTER -->
    <htmlpagefooter name="Footer" style="display:none;">
        <div style="width:100%; padding:20px 0; background:#fff; font-size: 12px; font-family: sans-serif;">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="border: none; width: 20%;">
                        <img src="https://londontfefiles.s3.amazonaws.com/assets/images/f_pdflogo.png" alt=""
                            style="max-width: 100px;">
                    </td>
                    <td style="border: none; width: 40%;">
                        <span style="color:#b49058; text-transform: uppercase;">Course Schedule</span><br />
                        <a href="https://www.londontfe.com"
                            style="text-decoration:none; color:#b49058; text-transform: uppercase;">londontfe.com</a>
                    </td>
                    <td style="border: none; width: 30%;">
                        <img src="https://londontfefiles.s3.amazonaws.com/assets/images/f_pdflogoaniv.png" alt=""
                            style="max-width: 100px;">
                    </td>
                    <td style="border: none; width: 10%; text-align: right; padding-top: 10px;">
                        0{PAGENO}
                    </td>
                </tr>
            </table>
        </div>
    </htmlpagefooter>

    <!-- PAGE 1 (Cover) -->
    <div class="havfoter">
        <div
            style="background:url(https://londontfefiles.s3.amazonaws.com/assets/images/page1.png) no-repeat center top; background-size:100% auto; width:100%; height:1100px;">
            <div class="introd">GLOBAL LEARNING</div>
            <h1 class="blw_hdre" style="padding-left:15px;">
                <span style="color:#b49057;">SETTING A NEW<br>STANDARD IN</span><br>EXCELLENCE
            </h1>
        </div>
    </div>

    <!-- PAGE 2 (Intro) -->
    <div class="havfoter">
        <div
            style="background:url(https://londontfefiles.s3.amazonaws.com/assets/images/engpage2.png) no-repeat center 45%; background-size:100% auto; width:100%; height:1200px;">
            <div class="page2-introd">introduction</div>
            <h1 class="page2-blw_hdre" style="padding-top:30px; font-size: 50px;">Transforming<br />the training
                <br />landscape
            </h1>
            <p class="bld_cont">
                Welcome to London Training for Excellence, the training company
                celebrating 10 years of excellence in empowering professionals
                worldwide! We take pride in being one of the UK’s premier training
                companies, offering over 300 courses globally in more than 30
                locations. We tailor programmes and solutions to deliver measurable,
                sustainable change and transform the training landscape for
                individuals and organisations.
            </p>
        </div>

        <div>
            <div class="page2-introd" style="padding:30px 0 0 40px;">Introduction</div>
            <div style="overflow: hidden;">
                <div class="outlin_lft_dv">
                    <p class="bld_cont_ag">At London Training for Excellence, relevant,
                        innovative programmes are vital to
                        improving performance and fostering new
                        perspectives. We offer real-life education
                        that inspires and encourages learning in
                        diverse environments. Our friendly team
                        becomes your training partner, coach,
                        and guide as you embark on a journey to
                        realise your full potential.</p>
                    <p class="bld_cont_ag">Over the past 10 years, we have honed our
                        industry expertise, business acumen, and
                        insight to craft solutions that maximise
                        participants’ productivity and enable them</p>
                </div>
                <div class="outlin_rit_dv">
                    <p class="bld_cont_ag">to deliver their full potential. Our programmes
                        reflect the need for practical solutions
                        and usable knowledge. Our course leaders
                        and instructors tailor content to coach and
                        guide you to identify and achieve your
                        desired outcomes.</p>
                    <p class="bld_cont_ag">Join us in celebrating our 10th anniversary by
                        embarking on your personal or organisational
                        learning journey with London Training for
                        Excellence. We can’t wait to be part of your
                        success story!</p>
                </div>
            </div>

            <div style="padding:20px 10px 50px 10px;">
                <div style="padding:30px;">
                    <img src="https://londontfefiles.s3.amazonaws.com/assets/images/engcon1.png"
                        style="max-width: 100%;">
                </div>
            </div>

            <p style="font-size:14px; padding:5px 0 5px 40px; text-transform:uppercase; color:#b49058;">Working in
                partnership with leading organisations:</p>
            <div style="padding:5px 40px 5px 40px;">
                <img src="https://londontfefiles.s3.amazonaws.com/assets/images/partnr-icne.png" alt=""
                    style="max-width: 100%;" />
            </div>
        </div>
    </div>

    <!-- PAGE 3 (Table) -->
    <htmlpageheader name="schedule_header" style="display:none;">
        <div style="margin-left: -10mm; margin-right: -10mm; line-height: 0;">
            <img src="https://londontfefiles.s3.amazonaws.com/assets/images/pdf_ban.png" style="width: 210mm; display: block;" alt="Banner" />
        </div>
        <div class="header-container" style="font-family: sans-serif; font-size: 24px; padding-top: 15px;">Upcoming Courses</div>
    </htmlpageheader>

    <div class="havfoterhed">
        <table class="schedule-table">
            <thead>
                <tr>
                    <th width="25%">Course Name</th>
                    <th width="25%">Categories Name</th>
                    <th width="15%">Date</th>
                    <th width="20%">Venue</th>
                    <th width="15%">Fee (£)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                    <tr>
                        <td>
                            <a href="{{ $course['link'] }}" target="_blank" style="color:black; text-decoration:underline;">
                                {{ $course['course_name'] }}
                            </a>
                        </td>
                        <td>{{ $course['category'] }}</td>
                        <td>{{ $course['date_formatted'] }}</td>
                        <td>{{ $course['venue'] }}</td>
                        <td>{{ $course['price'] > 0 ? '£' . $course['price'] : 'On Request' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>