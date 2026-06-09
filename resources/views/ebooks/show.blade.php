@extends('layouts.app')

@section('title', $ebook->title)

@section('content')
@php($formattedPrice = 'Rp ' . number_format($ebook->price, 0, ',', '.'))
<div style="background:linear-gradient(180deg,#f8fbff 0%,#eef4ff 100%); border-bottom:1px solid #dbe7ff; padding:18px 0;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size:.9rem;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#3f5cf6;text-decoration:none;font-weight:700;">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('home', ['category' => $ebook->category]) }}" style="color:#3f5cf6;text-decoration:none;font-weight:700;">{{ $ebook->category }}</a></li>
                <li class="breadcrumb-item active" style="color:#64748b;">{{ Str::limit($ebook->title, 40) }}</li>
            </ol>
        </nav>
    </div>
</div>

<section style="padding:56px 0 72px; background:#f8fbff;">
    <div class="container">
        <div style="background:#fff; border:1px solid #dbe7ff; border-radius:24px; box-shadow:0 24px 70px rgba(63,92,246,.10); overflow:hidden;">
            <div class="row g-0">
                <div class="col-lg-4" style="background:linear-gradient(180deg,#eef4ff 0%,#ffffff 100%); padding:36px;">
                    <div class="sticky-top" style="top:92px;">
                        @if($ebook->cover_url)
                            <img src="{{ $ebook->cover_url }}"
                                class="img-fluid w-100"
                                alt="{{ $ebook->title }}"
                                onerror="this.onerror=null; this.src='{{ $ebook->cover_fallback_url }}';"
                                style="border-radius:18px; box-shadow:0 22px 60px rgba(15,23,42,.18); max-height:520px; object-fit:cover;">
                        @else
                            <div style="height:420px; border-radius:18px; background:linear-gradient(135deg,#dbeafe,#c7d2fe); display:flex; align-items:center; justify-content:center; box-shadow:0 22px 60px rgba(15,23,42,.12);">
                                <i class="bi bi-book" style="font-size:5rem; color:#3f5cf6; opacity:.75;"></i>
                            </div>
                        @endif

                        <div style="background:#fff; border:1px solid #dbe7ff; border-radius:16px; padding:20px; margin-top:22px; text-align:center;">
                            <div style="font-size:1.7rem; font-weight:900; color:#3f5cf6;">{{ $formattedPrice }}</div>
                            <p style="color:#64748b; font-size:.85rem; margin-bottom:0;">Sekali beli - format PDF</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8" style="padding:42px 44px;">
                    <span style="display:inline-flex;align-items:center;gap:8px;background:#eef2ff;color:#3f5cf6;border-radius:999px;padding:8px 14px;font-size:.8rem;font-weight:900;letter-spacing:.04em;text-transform:uppercase;margin-bottom:18px;">
                        <i class="bi bi-bookmark"></i>{{ $ebook->category }}
                    </span>

                    <h1 style="font-family:'Nunito',sans-serif;font-size:clamp(2rem,4vw,3.2rem);font-weight:900;color:#0f172a;line-height:1.08;margin-bottom:14px;">
                        {{ $ebook->title }}
                    </h1>

                    <p style="color:#64748b;font-size:1.05rem;margin-bottom:24px;">
                        <i class="bi bi-person me-1"></i> oleh <strong style="color:#1e293b;">{{ $ebook->author }}</strong>
                    </p>

                    <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
                        <div style="color:#fbbf24;font-size:1.15rem;">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                        </div>
                        <span style="color:#64748b;">4.5 out of 5</span>
                        <span style="width:6px;height:6px;border-radius:50%;background:#cbd5e1;display:inline-block;"></span>
                        <span style="color:#64748b;"><i class="bi bi-filetype-pdf me-1"></i>PDF Format</span>
                    </div>

                    <div style="height:1px;background:#e2e8f0;margin:0 0 28px;"></div>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span style="font-size:2.3rem;font-weight:900;color:#3f5cf6;">{{ $formattedPrice }}</span>
                    </div>

                    <div class="mb-5">
                        @if($isPurchased)
                            <div style="background:#ecfdf5;border:1px solid #bbf7d0;border-radius:16px;padding:20px 24px;display:inline-flex;align-items:flex-start;gap:14px;">
                                <i class="bi bi-check-circle-fill text-success" style="font-size:1.5rem;margin-top:2px;"></i>
                                <div>
                                    <strong style="color:#15803d;">E-book ini sudah Anda miliki.</strong>
                                    <div class="mt-2 d-flex gap-3 flex-wrap" style="font-size:.9rem;">
                                        <a href="{{ route('library.download', $ebook) }}"
                                            style="background:#16a34a;color:#fff;border-radius:999px;padding:8px 20px;font-weight:800;text-decoration:none;display:inline-flex;align-items:center;gap:7px;">
                                            <i class="bi bi-download"></i> Download PDF
                                        </a>
                                        <a href="{{ route('library') }}"
                                            style="color:#16a34a;font-weight:800;text-decoration:none;padding:8px 0;display:inline-flex;align-items:center;gap:5px;">
                                            <i class="bi bi-collection"></i> Perpustakaan Saya
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            @auth
                                <div class="d-flex flex-wrap gap-3 align-items-center">
                                    <a href="{{ route('purchase.create', $ebook) }}"
                                        style="background:#3f5cf6;color:#fff;border:none;border-radius:999px;padding:15px 34px;font-weight:900;font-size:1rem;text-decoration:none;display:inline-flex;align-items:center;gap:9px;box-shadow:0 16px 30px rgba(63,92,246,.24);">
                                        <i class="bi bi-cart-plus"></i>
                                        Purchase - {{ $formattedPrice }}
                                    </a>
                                    <div style="font-size:.9rem;color:#64748b;">
                                        <i class="bi bi-shield-check me-1 text-success"></i>Akses langsung setelah pembayaran
                                    </div>
                                </div>
                            @else
                                <div class="d-flex flex-wrap gap-3">
                                    <a href="{{ route('login') }}"
                                        style="background:#3f5cf6;color:#fff;border-radius:999px;padding:15px 34px;font-weight:900;font-size:1rem;text-decoration:none;display:inline-flex;align-items:center;gap:9px;box-shadow:0 16px 30px rgba(63,92,246,.24);">
                                        <i class="bi bi-box-arrow-in-right"></i> Login untuk Membeli
                                    </a>
                                    <a href="{{ route('register') }}"
                                        style="background:#fff;color:#3f5cf6;border:1.5px solid #3f5cf6;border-radius:999px;padding:14px 30px;font-weight:900;font-size:1rem;text-decoration:none;display:inline-flex;align-items:center;gap:9px;">
                                        Buat Akun
                                    </a>
                                </div>
                            @endauth
                        @endif
                    </div>

                    <div style="background:#f8fbff;border-radius:18px;padding:28px;margin-bottom:24px;border:1px solid #dbe7ff;">
                        <h5 style="font-family:'Nunito',sans-serif;font-weight:900;margin-bottom:14px;color:#0f172a;">
                            <i class="bi bi-file-text me-2" style="color:#3f5cf6;"></i>Tentang Buku
                        </h5>
                        <p style="line-height:1.9;color:#334155;margin-bottom:0;">{{ $ebook->description }}</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div style="border:1px solid #dbe7ff;border-radius:16px;padding:20px;text-align:center;background:#fff;">
                                <i class="bi bi-person-fill" style="font-size:1.7rem;color:#3f5cf6;display:block;margin-bottom:8px;"></i>
                                <div style="color:#94a3b8;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Author</div>
                                <div style="font-weight:900;font-size:.92rem;color:#0f172a;">{{ $ebook->author }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div style="border:1px solid #dbe7ff;border-radius:16px;padding:20px;text-align:center;background:#fff;">
                                <i class="bi bi-tag-fill" style="font-size:1.7rem;color:#3f5cf6;display:block;margin-bottom:8px;"></i>
                                <div style="color:#94a3b8;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Category</div>
                                <div style="font-weight:900;font-size:.92rem;color:#0f172a;">{{ $ebook->category }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div style="border:1px solid #dbe7ff;border-radius:16px;padding:20px;text-align:center;background:#fff;">
                                <i class="bi bi-filetype-pdf" style="font-size:1.7rem;color:#3f5cf6;display:block;margin-bottom:8px;"></i>
                                <div style="color:#94a3b8;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Format</div>
                                <div style="font-weight:900;font-size:.92rem;color:#0f172a;">PDF</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
