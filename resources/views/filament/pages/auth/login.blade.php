<x-filament-panels::page.simple>
    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .dark .fi-sso-alert-error {
            border-color: rgba(127, 29, 29, 0.5) !important;
            background-color: rgba(69, 10, 10, 0.5) !important;
            color: #fca5a5 !important;
        }
        .dark .fi-sso-alert-status {
            border-color: rgba(30, 58, 138, 0.5) !important;
            background-color: rgba(23, 37, 84, 0.5) !important;
            color: #93c5fd !important;
        }
        .dark .fi-sso-emergency-box {
            border-color: rgba(146, 64, 14, 0.5) !important;
            background-color: rgba(69, 26, 3, 0.5) !important;
            color: #fde68a !important;
        }
        .dark .fi-sso-divider {
            border-top-color: #374151 !important;
        }
        .dark .fi-sso-caption {
            color: #9ca3af !important;
        }
    </style>

    {{-- Flash Error Notification --}}
    @if (session('error'))
        <div class="fi-sso-alert-error" style="margin-bottom: 1rem; display: flex; align-items: flex-start; gap: 0.75rem; border-radius: 0.75rem; border: 1px solid #fecaca; background-color: #fef2f2; padding: 0.875rem 1rem; font-size: 0.875rem; color: #b91c1c;">
            <svg width="20" height="20" style="width: 20px; height: 20px; min-width: 20px; min-height: 20px; flex-shrink: 0; margin-top: 2px; color: #ef4444;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <p style="font-weight: 600; margin: 0 0 0.125rem 0;">Gagal Masuk</p>
                <p style="font-size: 0.75rem; margin: 0; opacity: 0.9;">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if (session('status'))
        <div class="fi-sso-alert-status" style="margin-bottom: 1rem; display: flex; align-items: flex-start; gap: 0.75rem; border-radius: 0.75rem; border: 1px solid #bfdbfe; background-color: #eff6ff; padding: 0.875rem 1rem; font-size: 0.875rem; color: #1d4ed8;">
            <svg width="20" height="20" style="width: 20px; height: 20px; min-width: 20px; min-height: 20px; flex-shrink: 0; margin-top: 2px; color: #3b82f6;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p style="font-size: 0.75rem; margin: 0;">{{ session('status') }}</p>
            </div>
        </div>
    @endif

    {{-- Primary Action: SIPETRA SSO Hero Button --}}
    <div style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem; padding-top: 0.25rem; padding-bottom: 0.25rem;">
        <a href="{{ route('sipetra.login') }}"
           id="sipetra-sso-btn"
           onclick="if(this.getAttribute('data-submitting')) return false; this.setAttribute('data-submitting', '1'); this.style.pointerEvents='none'; this.style.opacity='0.85'; document.getElementById('sso-spinner').style.display='inline-block'; document.getElementById('sso-icon-badge').style.display='none'; document.getElementById('sso-btn-text').innerText='Menghubungkan ke SIPETRA...';"
           style="display: flex; align-items: center; justify-content: space-between; width: 100%; box-sizing: border-box; padding: 0.75rem 1.125rem; background-color: #005596; color: #ffffff; border-radius: 0.75rem; text-decoration: none; box-shadow: 0 4px 6px -1px rgba(0, 85, 150, 0.25), 0 2px 4px -2px rgba(0, 85, 150, 0.2); transition: all 0.2s ease; cursor: pointer; border: 1px solid #004982;"
           onmouseover="if(!this.getAttribute('data-submitting')){this.style.backgroundColor='#004b85'; this.style.boxShadow='0 8px 12px -2px rgba(0, 85, 150, 0.35)';}"
           onmouseout="if(!this.getAttribute('data-submitting')){this.style.backgroundColor='#005596'; this.style.boxShadow='0 4px 6px -1px rgba(0, 85, 150, 0.25), 0 2px 4px -2px rgba(0, 85, 150, 0.2)';}">
            
            <div style="display: flex; align-items: center; gap: 0.875rem;">
                {{-- Logo BPS Badge with Padding & Rounded Corners --}}
                <span id="sso-icon-badge" style="display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; min-width: 34px; min-height: 34px; border-radius: 8px; background-color: #ffffff; padding: 5px; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.08); flex-shrink: 0; box-sizing: border-box;">
                    <img id="sso-icon"
                         src="{{ asset('images/logo_bps.png') }}"
                         alt="Logo BPS"
                         style="width: 100%; height: 100%; object-fit: contain; display: block;"
                         loading="lazy">
                </span>

                {{-- Spinner --}}
                <svg id="sso-spinner" style="display: none; width: 28px; height: 28px; min-width: 28px; min-height: 28px; color: #ffffff; flex-shrink: 0; animation: spin 1s linear infinite;" viewBox="0 0 24 24" fill="none">
                    <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>

                <span id="sso-btn-text" style="font-size: 0.9375rem; font-weight: 600; color: #ffffff; letter-spacing: 0.015em;">Masuk dengan SIPETRA SSO</span>
            </div>

            <svg width="20" height="20" style="width: 20px; height: 20px; min-width: 20px; min-height: 20px; color: #bfdbfe; flex-shrink: 0; margin-left: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </a>

        <p class="fi-sso-caption" style="font-size: 0.75rem; text-align: center; color: #6b7280; line-height: 1.4; max-width: 22rem; margin: 0.25rem 0 0 0;">
            Autentikasi terpusat Badan Pusat Statistik Kabupaten Demak. Gunakan akun SIPETRA Anda untuk masuk secara aman.
        </p>
    </div>

    {{-- Emergency Fallback Collapsible Section --}}
    <div class="fi-sso-divider" style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;" x-data="{ show: @entangle('showFallback') }">
        <div style="text-align: center;">
            <button type="button"
                    @click="show = !show"
                    style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-size: 12px; color: #9ca3af; background: none; border: none; cursor: pointer; padding: 6px 12px; border-radius: 6px; transition: all 0.2s ease; outline: none;"
                    onmouseover="this.style.color='#4b5563'; this.style.backgroundColor='rgba(0,0,0,0.04)';"
                    onmouseout="this.style.color='#9ca3af'; this.style.backgroundColor='transparent';">
                <svg width="14" height="14" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Opsi Darurat / Pengembang</span>
                <svg width="14" height="14" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px; flex-shrink: 0; transition: transform 0.2s ease;"
                     :style="show ? 'transform: rotate(180deg);' : 'transform: rotate(0deg);'"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>

        <div x-show="show" x-collapse x-cloak style="margin-top: 1rem;">
            <div class="fi-sso-emergency-box" style="display: flex; align-items: flex-start; gap: 8px; padding: 10px 12px; margin-bottom: 16px; font-size: 12px; color: #92400e; background-color: #fef3c7; border: 1px solid #fde68a; border-radius: 8px; line-height: 1.4;">
                <svg width="16" height="16" style="width: 16px; height: 16px; min-width: 16px; min-height: 16px; flex-shrink: 0; margin-top: 1px; color: #d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Mode darurat hanya digunakan saat server SIPETRA tidak dapat diakses atau untuk pengujian lokal.</span>
            </div>

            {{ $this->content }}
        </div>
    </div>
</x-filament-panels::page.simple>
