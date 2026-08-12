import React, { useState, useEffect } from 'react';
import { createRoot } from 'react-dom/client';

const LandingPage = () => {
    const [scrolled, setScrolled] = useState(false);

    useEffect(() => {
        const handleScroll = () => {
            setScrolled(window.scrollY > 50);
        };
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    return (
        <div className="min-h-screen bg-slate-50 font-sans text-slate-800">
            {/* Navbar */}
            <nav className={`fixed w-full z-50 transition-all duration-300 ${scrolled ? 'bg-white/90 backdrop-blur-md shadow-sm py-3' : 'bg-transparent py-5'}`}>
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <div className="flex items-center gap-3">
                        {/* We use standard asset path from Laravel but we can't use blade helpers here directly. 
                            We will pass base URL or just use absolute path. Assuming /logo/... exists in public folder */}
                        <img src="/logo/Logo_Kementerian_Pekerjaan_Umum_Republik_Indonesia.svg" alt="Logo PUPR" className="h-10 w-10 drop-shadow-md" />
                        <div>
                            <h1 className={`font-bold text-lg leading-tight tracking-tight ${scrolled ? 'text-slate-900' : 'text-white drop-shadow-md'}`}>BPJN</h1>
                            <p className={`text-[10px] uppercase font-semibold tracking-wider ${scrolled ? 'text-blue-600' : 'text-blue-300 drop-shadow-md'}`}>Arsip & Kinerja</p>
                        </div>
                    </div>
                    <div>
                        <a href="/login" className={`inline-flex items-center justify-center px-6 py-2.5 font-semibold text-sm rounded-full transition-all shadow-sm hover:shadow-md ${scrolled ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-white text-blue-700 hover:bg-slate-100'}`}>
                            Masuk Sistem
                        </a>
                    </div>
                </div>
            </nav>

            {/* Hero Section */}
            <div className="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden min-h-[90vh] flex items-center">
                <div className="absolute inset-0 bg-blue-950">
                    <div className="absolute inset-0 opacity-40 mix-blend-overlay bg-[url('https://images.unsplash.com/photo-1541888081622-c943144e7314?q=80&w=2000&auto=format&fit=crop')] bg-cover bg-center"></div>
                    <div className="absolute inset-0 bg-gradient-to-t from-slate-50 via-blue-900/60 to-blue-950/80"></div>
                </div>

                <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-900/50 border border-blue-400/30 text-blue-200 text-xs font-semibold tracking-wide uppercase mb-6 backdrop-blur-sm">
                        <span className="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span>
                        Sistem Terintegrasi v2.0
                    </div>
                    <h1 className="text-4xl md:text-5xl lg:text-7xl font-extrabold text-white tracking-tight mb-6 leading-tight drop-shadow-lg">
                        Transparansi & Akuntabilitas <br className="hidden md:block" />
                        <span className="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-500">Pelaksanaan Jalan Nasional</span>
                    </h1>
                    <p className="mt-4 text-lg md:text-xl text-blue-100 max-w-3xl mx-auto mb-10 drop-shadow-md font-medium">
                        Platform digital untuk pengelolaan dan pemantauan dokumen keuangan, SPM, SP2D, hingga BAST secara real-time dan terukur.
                    </p>
                    <div className="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="/login" className="inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-blue-900 bg-yellow-400 hover:bg-yellow-300 rounded-full transition-all shadow-[0_0_20px_rgba(250,204,21,0.4)] hover:shadow-[0_0_30px_rgba(250,204,21,0.6)] hover:-translate-y-0.5">
                            Masuk ke Dashboard
                            <svg className="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                        <a href="#fitur" className="inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md rounded-full transition-all hover:-translate-y-0.5">
                            Pelajari Fitur
                        </a>
                    </div>
                </div>
                
                {/* Decorative Elements */}
                <div className="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
                    <svg className="relative block w-full h-[50px] md:h-[100px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                        <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118,130.85,123.63,194,115.3,238.13,109.52,281.42,88.7,321.39,56.44Z" className="fill-slate-50"></path>
                    </svg>
                </div>
            </div>

            {/* Live Stats */}
            <div className="relative -mt-16 z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-24">
                <div className="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 md:p-12">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-8 divide-y md:divide-y-0 md:divide-x divide-slate-100">
                        <div className="text-center px-4 pt-4 md:pt-0">
                            <p className="text-sm font-medium text-slate-500 uppercase tracking-wider mb-2">Total Pagu Berjalan</p>
                            <p className="text-4xl font-extrabold text-slate-800">Rp 4.2<span className="text-2xl text-slate-400">T</span></p>
                        </div>
                        <div className="text-center px-4 pt-4 md:pt-0">
                            <p className="text-sm font-medium text-slate-500 uppercase tracking-wider mb-2">Dokumen SPM</p>
                            <p className="text-4xl font-extrabold text-blue-600">1,245<span className="text-2xl text-blue-300">+</span></p>
                        </div>
                        <div className="text-center px-4 pt-4 md:pt-0">
                            <p className="text-sm font-medium text-slate-500 uppercase tracking-wider mb-2">Rata-rata SLA</p>
                            <p className="text-4xl font-extrabold text-emerald-500">1.5<span className="text-2xl text-emerald-300"> Hari</span></p>
                        </div>
                        <div className="text-center px-4 pt-4 md:pt-0">
                            <p className="text-sm font-medium text-slate-500 uppercase tracking-wider mb-2">Serapan Dana</p>
                            <p className="text-4xl font-extrabold text-yellow-500">78<span className="text-2xl text-yellow-300">%</span></p>
                        </div>
                    </div>
                </div>
            </div>

            {/* Fitur Utama */}
            <div id="fitur" className="py-16 bg-slate-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center max-w-3xl mx-auto mb-16">
                        <h2 className="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Solusi Digital Terpadu</h2>
                        <p className="text-lg text-slate-600">Sistem yang dirancang khusus untuk mempercepat birokrasi, mengamankan dokumen, dan menyajikan data seketika.</p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {/* Feature 1 */}
                        <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg transition-shadow">
                            <div className="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
                                <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 className="text-xl font-bold text-slate-800 mb-3">Arsip Dokumen Digital</h3>
                            <p className="text-slate-600">Penyimpanan aman untuk BAST, SPM, dan SP2D. Semua dokumen dapat ditelusuri kembali dalam hitungan detik tanpa membongkar lemari arsip.</p>
                        </div>

                        {/* Feature 2 */}
                        <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg transition-shadow">
                            <div className="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-6">
                                <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </div>
                            <h3 className="text-xl font-bold text-slate-800 mb-3">Dashboard Analitik</h3>
                            <p className="text-slate-600">Visualisasi grafis kinerja penyerapan pagu, komposisi SPM, hingga kecepatan verifikasi yang diupdate secara real-time.</p>
                        </div>

                        {/* Feature 3 */}
                        <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg transition-shadow">
                            <div className="w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mb-6">
                                <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <h3 className="text-xl font-bold text-slate-800 mb-3">Keamanan Multi-Peran</h3>
                            <p className="text-slate-600">Akses berlapis dengan sistem Role-Based Access Control (Admin, Atasan, Karyawan) memastikan integritas data tetap terjaga.</p>
                        </div>
                    </div>
                </div>
            </div>

            {/* Footer */}
            <footer className="bg-slate-900 py-12 border-t border-slate-800">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col md:flex-row justify-between items-center gap-6">
                        <div className="flex items-center gap-3">
                            <img src="/logo/Logo_Kementerian_Pekerjaan_Umum_Republik_Indonesia.svg" alt="Logo PUPR" className="h-10 w-10 opacity-80" />
                            <div>
                                <h4 className="text-slate-200 font-bold">Balai Pelaksanaan Jalan Nasional</h4>
                                <p className="text-slate-500 text-sm">Kementerian Pekerjaan Umum dan Perumahan Rakyat</p>
                            </div>
                        </div>
                        <div className="text-slate-500 text-sm text-center md:text-right">
                            &copy; {new Date().getFullYear()} Sistem Informasi BPJN. Hak Cipta Dilindungi.
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    );
};

// Mount component
const rootElement = document.getElementById('react-root');
if (rootElement) {
    const root = createRoot(rootElement);
    root.render(<LandingPage />);
}
