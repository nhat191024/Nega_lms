<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giấy chứng nhận</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f8f9fa;
        }
        .certificate {
            position: absolute;
            width: 900px;
            height: 500px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 10px solid #9cab2d;
            padding: 30px;
            background: url('{{ public_path('img/pdfs/background.png') }}') no-repeat center center fixed;
            background-size: cover;
            background-size: 100% 100%;
        }
        .certificate-header {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 30px;
        }
        .certificate-header h2 {
            font-size: 23px;
        }
        .certificate-header p {
            font-size: 14px;
        }
        .certificate-body {
            text-align: center;
            margin-top: 40px;
        }
        .certificate-body h1 {
            font-size: 30px;
        }
        .certificate-body h2 {
            font-size: 24px;
        }
        .certificate-body h3 {
            font-size: 20px;
            margin-top: 50px;
        }
        .certificate-body h4 {
            font-size: 20px;
            margin-top: 10px;
        }
        .certificate-body p {
            font-size: 16px;
            margin-top: 25px;
        }
        .certificate-footer {
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding-bottom: 50px
        }
        .signature {
            text-align: right;
        }
        .signature img {
            max-width: 120px;
        }
        .seal {
            text-align: left;
        }
        .seal img {
            max-width: 80px;
        }
        /* .cover {
            position: absolute;
            width: 180px;
            line-height: 30px;
            background-color: rgb(197, 186, 86);
            top: 82%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 999;
            text-align: center;
            color: blue;
        } */
        @media (max-width: 768px) {
            .certificate {
                width: 100%;
            }
        }
    </style>
</head>
<body>
{{-- <div class="cover">{{ $name_cty }}</div> --}}
<div class="certificate">
    <div class="certificate-header">
        <h2>Giấy chứng nhận</h2>
    </div>
    <div class="certificate-body">
        <h1>{{ $name_cty }}</h1>
        <h3>{{ $name_student }}</h3>
        <h4>Đã tham gia và hoàn thành khóa học</h4>
        <h2>{{ $name_class }}</h2>
        <p>Ngày cấp: {{ $issue_date }}</p>
    </div>
    <div class="certificate-footer">
        <div class="seal">
            <img src="https://via.placeholder.com/80x80" alt="Seal">
        </div>
        <div class="signature">
            <img src="https://via.placeholder.com/120x60" alt="Signature">
            <p>Giám đốc kí tên</p>
            <p>Vũ Anh Nhật</p>
        </div>
    </div>
</div>

</body>
</html>
