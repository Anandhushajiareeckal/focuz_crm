<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Offer Letter - Focuz Academy</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            color: #333;
            background: #e0e0e0;
            padding: 10mm;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 0 auto 10mm auto;
            padding: 10mm 13mm;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            page-break-after: always;
            position: relative;
            padding-left: 54px;
            padding-right: 54px;
            padding-top: 25px;
        }

        /* Watermark Logo in Center */
        .page::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 383px;
            height: 308px;
            background: url('{{ asset('images/logo.jpg') }}') no-repeat center center;
            background-size: contain;
            opacity: 0.07;
            /* Faded effect */
            transform: translate(-50%, -50%);
            z-index: 0;
            /* Behind content */
        }

        /* Make sure page contents appear above watermark */
        .page * {
            position: relative;
            z-index: 1;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 90px;
            gap: 20px;
            position: relative;
        }

        .logo {
            width: 138px;
            height: 116px;
            position: absolute;
            top: 5px;
            left: -30px;
        }

        .header-text h1 {
            text-align: center;
            width: 223%;
            font-size: 41px;
            font-weight: bold;
            color: #4472C4;
            margin-top: 25px;
            /* pushes text down */
        }

        /* Section Titles */
        .section-title {
            margin: 12px 0 10px 0;
            color: #4472C4;
            font-size: 20px;
            font-weight: bold;
        }

        /* Greetings */
        .dear-student {
            color: #4472C4;
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
        }

        .greeting p {
            margin-bottom: -2px;
            font-size: 14px;
        }

        .content-text {
            font-size: 14px;
            margin-bottom: 10px;
            text-align: justify;
            line-height: 1.6;
        }

        /* Student Table */
        .student-details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1px 0;
            font-size: 15px;
        }

        .student-details-table td {
            border: 1px solid #999;
            padding: 6px 21px;
        }

        .student-details-table td:first-child {

            font-weight: normal;
            width: 45%;
        }


        .footer {
            text-align: center;
            color: #000;
            font-weight: bold;
            font-size: 14px;
            padding-top: 27px;
        }


        /* Additional Styles */
        ul {
            margin-left: 20px;
            font-size: 11px;
            line-height: 1.8;
        }

        li {
            margin-bottom: 5px;
        }

        .highlight-box {
            background: #e6f2ff;
            border: 1px solid #4472C4;
            padding: 12px;
            margin: 12px 0;
            font-size: 11px;
        }

        .email-link {
            color: #4472C4;
            text-decoration: none;
        }

        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 12px 0;
        }

        .column-box {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 11px;
        }

        .column-box h4 {
            color: #4472C4;
            margin-bottom: 8px;
            font-size: 12px;
        }

        .executive-box {
            border: 2px solid #4472C4;
            padding: 15px;
            margin: 15px 0;
        }

        .executive-table {
            width: 100%;
            font-size: 11px;
        }

        .executive-table td {
            padding: 6px 0;
        }

        .student-details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 13px;
            /* smaller font */
        }


        .student-details-table td:first-child {
            font-weight: normal;
            width: 40%;
        }

        .note-box {
            background: #fff9e6;
            border: 1px solid #ffcc00;
            padding: 12px;
            margin: 15px 0;
            font-size: 10px;
            line-height: 1.6;
        }

        .declaration-box {
            border: 2px solid #ffcc00;
            background: #fffef0;
            padding: 15px;
            margin: 15px 0;
            font-size: 11px;
        }

        .signature-section {
            margin-top: 20px;
        }

        .signature-row {
            display: flex;
            gap: 30px;
            margin-bottom: 15px;
        }

        .signature-field {
            flex: 1;
        }

        .signature-field label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 25px;
        }

        .student-details-table a {
            color: #4472C4;
            /* blue color */
            text-decoration: none;
            /* optional: remove underline */
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .page {
                margin: 0;
                box-shadow: none;
                padding: 15mm 20mm;
            }
        }
    </style>
</head>

<body>
    <!-- PAGE 1 -->
    <div class="page">
        <div class="header">
            <img src="{{ public_path('logo.png') }}" alt="Focuz Academy Logo" class="logo">
            <div class="header-text">
                <h1>Focuz Academy</h1>
            </div>
        </div>

        <div class="section-title">Course Offer Letter</div>

        <div class="greeting">

            <div class="section-title">Dear Student,</div>
            <p>A warm greeting from Focuz Academy !!!</p>
            <p> We would like to inform you that you have duly registered for the educational programe.</p>
        </div>

        <div class="section-title">About Focuz</div>
        <p class="content-text"> The Focuz Academy is one of the pioneers in distance education and super child of a
            giant education
            entity, Brillainz Education Group, based in UAE. Focuz Academy is well - versed in providing state-of
            the art learning infrastructure and outstanding services at your convenience. We do facilitate a wide
            range of career choice in distance education across Kerala. We provide all sorts of educational services
            such as Distance Education, University Admission and Educational Consulting.<br><br>
            We are very pleased to inform you that you are duly registered in our couse and that your registration
            details will be as follows:</p>

        <div class="section-title">Student Details</div>
        <table class="student-details-table">
            <tr>
                <td>Name of the Student</td>
                <td>{{ $student->first_name . ' '. $student->last_name}}</td>
            </tr>
            <tr>
                <td>Registered Mobile Number</td>
                <td>{{ $student->phone_number}}</td>
            </tr>
            
            <tr>
                <td>Email Address</td>
                <td>{{ $student->email}}</td>
            </tr>
            <tr>
                <td>Course / Specialization</td>
                <td>BA SOCIOLOGY/HISTORY, POLITICAL SCIENCE</td>
            </tr>
            <tr>
                <td>University</td>
                <td>SVSU</td>
            </tr>
            <tr>
                <td>Center</td>
                <td>TRIVADRAM</td>
            </tr>
            <tr>
                <td>Track ID</td>
                <td>45,000/-</td>
            </tr>
            <tr>
                <td>Piad</td>
                <td>7,500/-</td>
            </tr>
            <tr>
                <td>Initial Receipt Number</td>
                <td>16,688</td>
            </tr>
            </tr>
            <tr>
                <td>Admission Executive </td>
                <td>JINCY</td>
            </tr>
            </tr>
            <tr>
                <td>Customer Relation Executive</td>
                <td>ATHIRA</td>
            </tr>
            </tr>
            <tr>
                <td>Contact Number</td>
                <td>+91 8086652555</td>
            </tr>
            </tr>
            <tr>
                <td>Email</td>
                <td><a href="mailto:abhiachuzvz007@gmail.com">abhiachuzvz007@gmail.com</a></td>
            </tr>


        </table>

        <div class="footer">
            <p>www.focuzacademy.com</p>
        </div>
    </div>


    <!-- PAGE 2 -->
    <div class="page">
        <div class="header">
            <img src="{{ public_path('logo.png') }}" alt="Focuz Academy Logo" class="logo">
            <div class="header-text">
                <h1></h1>
            </div>
        </div>

        <div class="section-title">Course Phases</div>
        <p class="content-text">Your course has different phases that make it easier to complete a degree certificate.
            All of these phases are illustrated as follow.</p>

        <div class="phase-section">
            <div class="section-title">1. Admission Phase</div>

            <p class="content-text">This is the first step, once you have completed your admission discussion at an
                initial fee, you will be registered with focuz and will be assigned to a student relation executive.</p>
            <p class="content-text">Once the center registration has been completed, you will be provided with a track
                ID, through which we can track your application in the center. For the purpose of university
                registration, we ask all our student to send a clear copy of the documents listed below to the email ID,
                Indicating the name and track ID as subject to:<br>
                <a href="mailto:focuz.admissiondocs@gmail.com" class="email-link">focuz.admissiondocs@gmail.com</a>
            </p>
            <ul>
                <li>Secondary Certificate</li>
                <li>Higher secondary certificate or equivalent</li>
                <li>Degree certificate (for master students)</li>
                <li>Aadhar front and back page</li>
                <li>Passport size photo</li>
            </ul>

            <div class="highlight-box">
                <strong style="color: #4472C4;">Immediate Services after Admission</strong>
                <ul style="margin-top: 8px;">
                    <li>Student will receive receipt on same day of registration.</li>
                    <li>Student will receive course offer letter through mail on next day of admission.</li>
                    <li>Each Student will assigned to a student relation executive and CRE will be contacting the
                        student on the next day of admission.</li>
                    <li>Student will receive an invitation for weekly lectures.</li>
                    <li>On the next day of admission, University assignment question with guidelines will be receiving
                        in students registered mail id.</li>
                </ul>
                <p style="margin-top: 10px;"><strong>If any service not received on next day of admission, students can
                        mail to:</strong><br>
                    <a href="mailto:focuz.solutions@gmail.com" class="email-link">focuz.solutions@gmail.com</a>
                </p>
            </div>
        </div>

        <div class="phase-section">
            <div class="phase-title">2. Registration Phase</div>
            <p class="content-text">Minimum fee of university registration will be first year fee.</p>
            <p class="content-text">Students can check their University registration, Photo verification, Course &
                Specialization verification, Study material access, Pre-recorded classes, Exam notification, Exam
                result, etc...</p>
            <p class="content-text">University registration will be completing within four week of admission.</p>

            <div class="two-column">
                <div class="column-box">
                    <h4>Total fee includes:</h4>
                    <ul>
                        <li>University Registration</li>
                        <li>Academic Registration</li>
                        <li>Examination Fee</li>
                        <li>Specialization Fee</li>
                        <li>Documentation Fee</li>
                        <li>Service Charge</li>
                    </ul>
                </div>
                <div class="column-box">
                    <h4>Total fee not includes:</h4>
                    <ul>
                        <li>Final Certificate Fee</li>
                        <li>Back Paper Fee<br>(If student fail/absent for any subject)</li>
                    </ul>
                </div>
            </div>

            <p class="content-text">Once completing LMS, student can access text book soft copy in their university
                portal.</p>
            <p class="content-text">University text books will only issued to the students after the center
                registration. There will be an extra charge for text book (Hard copy). Once you get the text books
                student should give the students affairs executive the acknowledgement.</p>
        </div>

        <div class="footer">
            <p>www.focuzacademy.com</p>
        </div>
    </div>

    <!-- PAGE 3 -->
    <div class="page">
        <div class="header">
            <svg class="logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="48" fill="#4472C4" stroke="#2E5C9A" stroke-width="2" />
                <path d="M30 45 L50 25 L70 45 L65 45 L65 65 L55 65 L55 50 L45 50 L45 65 L35 65 L35 45 Z"
                    fill="white" />
                <path d="M50 35 L45 40 L55 40 Z" fill="#FFD700" />
                <circle cx="50" cy="70" r="3" fill="white" />
                <text x="50" y="88" font-size="8" fill="white" text-anchor="middle" font-weight="bold">FOCUZ</text>
            </svg>
            <div class="header-text">
                <h1>Focuz Academy</h1>
            </div>
        </div>

        <div class="phase-section">
            <div class="phase-title">3. Study Materials</div>
            <p class="content-text">University Registration begins as per the notification from the university and end
                on or before a specified date, which will be updated by your student's affairs executive.</p>
            <p class="content-text">First semester fee has to be paid one month prior to university registration. If you
                do not meet the criteria, your application will not be registered and proceed to the next batch.</p>
            <p class="content-text">Student has to make sure we received all your educational documents via email, as
                mentioned in the initial admission stage.</p>
        </div>

        <div class="phase-section">
            <div class="phase-title">4. University Registration Phase</div>
            <p class="content-text">University registration process will commence after receiving all necessary
                documents and fees.</p>
        </div>

        <div class="phase-section">
            <div class="phase-title">5. Assignment</div>
            <p class="content-text">Students must receive an acknowledgement from executives that their assignment or
                projects have been collected and accepted in accordance with the guidelines.</p>
            <p class="content-text">The Assignment should be hand written, and all subject of the semester should be
                bound together and not separate.</p>
            <p class="content-text"><strong>The submission of assignment is mandatory one month prior to examination
                    date. If not, there will be semester back paper fee.</strong></p>
            <p class="content-text">After the submission only one examination hall ticket will be issued.</p>
            <p class="content-text">Assignment questions and guidelines are available in student's portal.</p>
        </div>

        <div class="phase-section">
            <div class="phase-title">6. Project Work</div>
            <p class="content-text">Project work is compulsory for student of final year.</p>
            <p class="content-text">Students must strictly follow the guidelines to prepare a project.</p>
            <p class="content-text"><strong>The project submission is mandatory one month prior to examination date. If
                    not, there will be a chance of failure.</strong></p>
        </div>

        <div class="phase-section">
            <div class="phase-title">7. Examination Phase</div>
            <p class="content-text">A qualifying fee have to be paid one month prior to the registration date of the
                examination at the university.</p>
            <p class="content-text">Academy will be registering student after checking their fee eligibility and
                assignment status.</p>
            <p class="content-text">Students will receive examination time table, Examination writing guidelines, and
                hall tickets before the examination itself.</p>
            <p class="content-text"><strong>All students should strictly adhere to exam rules and regulations.</strong>
            </p>
        </div>

        <div class="phase-section">
            <div class="phase-title">8. Result & Mark List Phase</div>
            <p class="content-text">After the examination, the university will publish the result within three months,
                our center will not be responsible for any delay on the part of the university.</p>
            <p class="content-text">If you do not attend a minimum of 3 subject per semester, your academic year will be
                extended to the following year and you will have to pay the back-paper registration fee to reappear the
                examination.</p>
            <p class="content-text">The date and venue of the convocation ceremony shall be fixed by the university.
                There would be a fee for convocation and final certificate.</p>
        </div>

        <div class="footer">
            <p>www.focuzacademy.com</p>
        </div>
    </div>

    <!-- PAGE 4 -->
    <div class="page">
        <div class="header">
            <svg class="logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="48" fill="#4472C4" stroke="#2E5C9A" stroke-width="2" />
                <path d="M30 45 L50 25 L70 45 L65 45 L65 65 L55 65 L55 50 L45 50 L45 65 L35 65 L35 45 Z"
                    fill="white" />
                <path d="M50 35 L45 40 L55 40 Z" fill="#FFD700" />
                <circle cx="50" cy="70" r="3" fill="white" />
                <text x="50" y="88" font-size="8" fill="white" text-anchor="middle"
                    font-weight="bold">FOCUZ</text>
            </svg>
            <div class="header-text">
                <h1>Focuz Academy</h1>
            </div>
        </div>

        <div class="phase-section">
            <div class="phase-title">9. Convocation & Degree Certificate</div>
            <p class="content-text">If, for any reason the student does not attend the convocation ceremony, they shall
                be asked to pay the Provisional certificate fee and to collect the certificate directly from the
                academy.</p>
            <p class="content-text">Our service extends until the end of the semester. Students have the option to
                apply for the main degree certificate directly at the university, or we can apply on their behalf, with
                charges applicable for the service.</p>
        </div>

        <div class="phase-section">
            <div class="phase-title">10. Reference Claim</div>
            <p class="content-text">Students can refer their friend or relatives to the academy after after their
                successful enrollment they can claim 1500/- amount as discount voucher in their total fee and if the
                referee is not a student then can claim 100% of amount as cash.</p>
        </div>

        <div class="section-title">Points to Remember</div>
        <ul class="points-list">
            <li>Students should keep all receipts issued from academy voucher.</li>
            <li>Students should complete their semester fee one month prior the examination.</li>
            <li>Fee needs to be remitted through official mediums, its compulsory to collect receipts against every
                remittance.</li>
            <li>Focuz would not be responsible for any type of staff commitments like assignments project etc...</li>
            <li>If Students is not submitting assignment project on time, academy won't be responsible for further
                consequence.</li>
            <li>It is the responsibility of the student to submit genuine, approved Fee - certification on time for the
                university registration.</li>
        </ul>

        <div class="section-title">Student's Affairs Executive Details</div>
        <div class="executive-box">
            <table class="executive-table">
                <tr>
                    <td>NAME</td>
                    <td>: [Executive Name]</td>
                </tr>
                <tr>
                    <td>CONTACT NO.</td>
                    <td>: [Contact Number]</td>
                </tr>
                <tr>
                    <td>EMAIL ID</td>
                    <td>: [Email Address]</td>
                </tr>
                <tr>
                    <td>BRANCH</td>
                    <td>: Focuz Academy, Kochi</td>
                </tr>
            </table>
        </div>

        <div class="note-box">
            <p>If you feel free to convey your suggestions or complaints, Don't hesitate to contact us. For any further
                queries regarding details of the course, suggestion and complaints, feel free to contact our whats app
                assistance number @</p>
            <p style="margin-top: 8px;"><strong>(Note: Dear Students,</strong> kindly make sure to either acknowledge
                the mail or signing and send back the signed softcopy to the same mail. If we are unable to receive it
                will consider as verified and acknowledge it from your end.</p>
            <p style="margin-top: 8px;">Until and unless there is rejection from the university side, there won't be no
                refund approved.)</p>
        </div>

        <div class="section-title">Declaration</div>
        <div class="declaration-box">
            <p>I hereby declare that, I accept and agree all the terms and conditions in the offer letter and will abide
                all the rules and regulations of the institute correctly. I understood that, if any kind of delay from
                any side which affect successful completion of my course the center would not be responsible.</p>

            <div class="signature-section">
                <div class="signature-row">
                    <div class="signature-field">
                        <label>Name of student :</label>
                        <div class="signature-line"></div>
                    </div>
                    <div class="signature-field">
                        <label>Date :</label>
                        <div class="signature-line"></div>
                    </div>
                </div>
                <div class="signature-field">
                    <label>Sign :</label>
                    <div class="signature-line"></div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p><strong>Focuz Academy</strong></p>
            <p>www.focuzacademy.com</p>
        </div>
    </div>
</body>

</html>
