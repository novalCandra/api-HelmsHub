<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>PDF Interface | Laravel PDF Testing Suite</title>
    <!-- Bootstrap 5 CSS + Icons + Nice Font -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 (free icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Font: Inter for clean modern look -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap"
        rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f4f7fc;
        }

        /* card styling & subtle shadows */
        .pdf-card {
            background: white;
            border-radius: 28px;
            border: none;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s ease;
            overflow: hidden;
        }

        .pdf-header {
            background: linear-gradient(135deg, #1e2a3a 0%, #0f172a 100%);
            color: white;
            padding: 1.8rem 2rem;
            border-radius: 28px 28px 0 0;
        }

        .badge-laravel {
            background-color: #ff2d20;
            font-weight: 500;
            letter-spacing: 0.3px;
            padding: 6px 12px;
            border-radius: 40px;
            font-size: 0.75rem;
        }

        .pdf-preview-area {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.02);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }

        .pdf-content-simulation {
            background: white;
            padding: 2rem 2rem;
            border-radius: 20px;
        }

        .invoice-row {
            border-bottom: 1px solid #eef2f6;
            padding: 0.75rem 0;
        }

        .total-row {
            border-top: 2px dashed #cbd5e1;
            margin-top: 1rem;
            padding-top: 1rem;
            font-weight: 700;
        }

        .btn-pdf {
            border-radius: 60px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-pdf-primary {
            background: #1e2a3a;
            border: none;
            color: white;
        }

        .btn-pdf-primary:hover {
            background: #0f172a;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
        }

        .btn-pdf-outline {
            border: 1.5px solid #cbd5e1;
            background: white;
            color: #1e293b;
        }

        .btn-pdf-outline:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        footer {
            font-size: 0.8rem;
            color: #5b6e8c;
        }

        hr {
            opacity: 0.5;
        }

        .table-custom th {
            font-weight: 600;
            color: #334155;
            border-bottom-width: 1px;
        }

        .status-badge {
            background: #e6f7e6;
            color: #2b6e3c;
            font-size: 0.7rem;
            border-radius: 50px;
            padding: 4px 10px;
            font-weight: 500;
        }

        @media print {

            /* hide buttons and actions when physically printing */
            .no-print,
            .action-buttons,
            .btn,
            .pdf-header .btn,
            .card-footer {
                display: none !important;
            }

            body {
                background: white;
                padding: 0;
                margin: 0;
            }

            .pdf-card {
                box-shadow: none;
                border-radius: 0;
            }

            .pdf-preview-area {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>

<body>

    <div class="container-lg">
        <!-- Laravel context hint + Interface header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-semibold" style="color: #0f172a;">
                    <i class="fas fa-file-pdf text-danger me-2"></i> PDF Data Helm - HELM HUB
                </h3>
            </div>
            <div>
                <span class="badge-laravel"><i class="fab fa-laravel me-1"></i> Laravel Compatible</span>
            </div>
        </div>

        <!-- Main PDF card: represents a 'view' that can be converted to PDF using DomPDF/ Snappy or browser print -->
        <div class="pdf-card mb-5">
            <div class="pdf-header d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 fw-semibold"><i class="fas fa-receipt me-2"></i> HELM Invoice</h4>
                </div>
                <div class="mt-2 mt-sm-0">
                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill"><i
                            class="far fa-check-circle text-success me-1"></i> Ready for PDF rendering</span>
                </div>
            </div>

            <!-- PDF CONTENT AREA: everything inside this div will be captured as PDF (nice bootstrap design) -->
            <div class="pdf-preview-area m-4 p-3 p-md-4" id="pdfContent">
                <div class="pdf-content-simulation">
                    <!-- Company & client info row (Bootstrap grid) -->
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-building fa-lg" style="color:#2c3e66;"></i>
                                <h5 class="fw-bold mb-0">HELM HUB</h5>
                            </div>
                            <p class="small text-secondary">{{ $userPDF->email ?? 'hello@laraveltech.test' }} |
                                {{ $userPDF->phone_number ?? '+1 (555)789-1234}' }} </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="mb-2">
                                <span class="fw-semibold">Kepada</span>
                            </div>
                            <p class="fw-medium mb-1">{{ $userPDF->full_name ?? 'Quest' }}</p>
                        </div>
                    </div>

                    <!-- Items table - modern Bootstrap table -->
                    <div class="table-responsive">
                        <table class="table table-custom align-middle">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 5%">#</th>
                                    <th scope="col" style="width: 45%">HELM</th>
                                    <th scope="col" style="width: 15%">Daily Price</th>
                                    <th scope="col" style="width: 15%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($HelmAll as $HelmAlls)
                                    <tr>
                                        <td>{{ $HelmAlls->id }}</td>
                                        <td><span class="fw-medium">{{ $HelmAlls->helmet_name ?? '-' }}</span>
                                        </td>
                                        <td>Rp.{{ $HelmAlls->daily_price ?? '0' }}</td>
                                        <td>{{ $HelmAlls->status ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-2xl text-info">DATA TIDAK ADA</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer note inside PDF content -->
                    <hr class="my-4">
                </div>
            </div>
        </div>
    </div>
    <footer class="text-center">
        <p class="small">HELM-HUB</p>
    </footer>
</body>
