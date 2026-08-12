import React from 'react';
import { createRoot } from 'react-dom/client';

const Dashboard = ({ user, stats, recentLogs, sysInfo }) => {
    
    const formatCurrency = (value) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(value);
    };

    const renderKaryawanView = () => (
        <div className="space-y-6">
            <div className="bg-blue-600 rounded-3xl p-8 text-white shadow-lg relative overflow-hidden">
                <div className="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
                <h2 className="text-3xl font-bold mb-2 relative z-10">Selamat Bekerja, {user.name}! 🚀</h2>
                <p className="text-blue-100 relative z-10 text-lg">Semangat menginput dokumen hari ini. Pastikan semua data akurat.</p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div className="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <div className="flex items-center gap-4 mb-4">
                        <div className="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <p className="text-sm text-slate-500 font-medium">SPM Terinput</p>
                            <p className="text-2xl font-bold text-slate-800">{stats.total_spm}</p>
                        </div>
                    </div>
                </div>
                
                <div className="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <div className="flex items-center gap-4 mb-4">
                        <div className="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p className="text-sm text-slate-500 font-medium">SP2D Terverifikasi</p>
                            <p className="text-2xl font-bold text-slate-800">{stats.total_sp2d}</p>
                        </div>
                    </div>
                </div>

                <div className="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <div className="flex items-center gap-4 mb-4">
                        <div className="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                        </div>
                        <div>
                            <p className="text-sm text-slate-500 font-medium">Total BAST</p>
                            <p className="text-2xl font-bold text-slate-800">{stats.total_basts}</p>
                        </div>
                    </div>
                </div>
            </div>

            <h3 className="text-xl font-bold text-slate-800 mt-8 mb-4">Aksi Cepat</h3>
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="/spm/create" className="flex items-center justify-center gap-2 bg-blue-50 hover:bg-blue-100 text-blue-700 py-4 px-6 rounded-2xl transition font-semibold border border-blue-100">
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4"></path></svg>
                    Input SPM Baru
                </a>
                <a href="/basts/create" className="flex items-center justify-center gap-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 py-4 px-6 rounded-2xl transition font-semibold border border-emerald-100">
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4"></path></svg>
                    Input BAST Baru
                </a>
                <a href="/sp2d/create" className="flex items-center justify-center gap-2 bg-purple-50 hover:bg-purple-100 text-purple-700 py-4 px-6 rounded-2xl transition font-semibold border border-purple-100">
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4"></path></svg>
                    Input SP2D Baru
                </a>
            </div>
        </div>
    );

    const renderAtasanView = () => (
        <div className="space-y-6">
            <div className="bg-slate-800 rounded-3xl p-8 text-white shadow-lg relative overflow-hidden">
                <div className="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-5 rounded-full blur-2xl"></div>
                <h2 className="text-3xl font-bold mb-2 relative z-10">Ringkasan Kinerja, {user.name}</h2>
                <p className="text-slate-300 relative z-10 text-lg">Pantau serapan anggaran dan status pelaksanaan jalan nasional secara real-time.</p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm flex flex-col justify-center">
                    <p className="text-sm text-slate-500 font-bold uppercase tracking-wider mb-2">Nilai Serapan SPM</p>
                    <p className="text-4xl font-extrabold text-blue-600 mb-2">{formatCurrency(stats.nilai_spm)}</p>
                    <p className="text-sm text-slate-500">Dari {stats.total_spm} dokumen SPM yang masuk.</p>
                </div>
                <div className="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm flex flex-col justify-center">
                    <p className="text-sm text-slate-500 font-bold uppercase tracking-wider mb-2">Pencairan SP2D (Sah)</p>
                    <p className="text-4xl font-extrabold text-emerald-600 mb-2">{formatCurrency(stats.nilai_sp2d)}</p>
                    <p className="text-sm text-slate-500">Dari {stats.total_sp2d} dokumen SP2D terverifikasi.</p>
                </div>
            </div>

            <h3 className="text-xl font-bold text-slate-800 mt-8 mb-4">Laporan Cepat</h3>
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="/laporan/realisasi-pagu" className="bg-white hover:bg-slate-50 border border-slate-200 p-4 rounded-2xl shadow-sm transition flex flex-col items-center text-center gap-3">
                    <div className="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center">
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <span className="font-semibold text-slate-700 text-sm">Realisasi Pagu</span>
                </a>
                <a href="/laporan/waktu-proses" className="bg-white hover:bg-slate-50 border border-slate-200 p-4 rounded-2xl shadow-sm transition flex flex-col items-center text-center gap-3">
                    <div className="w-10 h-10 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center">
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span className="font-semibold text-slate-700 text-sm">Kinerja SLA</span>
                </a>
                <a href="/laporan/status-dokumen" className="bg-white hover:bg-slate-50 border border-slate-200 p-4 rounded-2xl shadow-sm transition flex flex-col items-center text-center gap-3">
                    <div className="w-10 h-10 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center">
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <span className="font-semibold text-slate-700 text-sm">Status Dokumen</span>
                </a>
                <a href="/laporan/tagihan-outstanding" className="bg-white hover:bg-slate-50 border border-slate-200 p-4 rounded-2xl shadow-sm transition flex flex-col items-center text-center gap-3">
                    <div className="w-10 h-10 bg-cyan-100 text-cyan-600 rounded-full flex items-center justify-center">
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span className="font-semibold text-slate-700 text-sm">Outstanding</span>
                </a>
            </div>
        </div>
    );

    const renderAdminView = () => (
        <div className="space-y-6">
            <div className="bg-gradient-to-r from-purple-700 to-indigo-800 rounded-3xl p-8 text-white shadow-lg relative overflow-hidden">
                <div className="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
                <h2 className="text-3xl font-bold mb-2 relative z-10">Sistem Beroperasi, {user.name}! ⚙️</h2>
                <p className="text-purple-200 relative z-10 text-lg">Kelola pengguna, master data, dan pantau log aktivitas sistem.</p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div className="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm text-center">
                    <div className="w-12 h-12 bg-purple-100 text-purple-600 rounded-full mx-auto flex items-center justify-center mb-3">
                        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <p className="text-3xl font-extrabold text-slate-800">{stats.total_users}</p>
                    <p className="text-sm text-slate-500 font-medium">Pengguna Aktif</p>
                </div>
                
                <div className="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm text-center">
                    <div className="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full mx-auto flex items-center justify-center mb-3">
                        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <p className="text-3xl font-extrabold text-slate-800">{stats.total_satker}</p>
                    <p className="text-sm text-slate-500 font-medium">Satuan Kerja</p>
                </div>

                <div className="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm text-center">
                    <div className="w-12 h-12 bg-rose-100 text-rose-600 rounded-full mx-auto flex items-center justify-center mb-3">
                        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p className="text-3xl font-extrabold text-slate-800">{stats.total_spm}</p>
                    <p className="text-sm text-slate-500 font-medium">Total Dokumen</p>
                </div>

                <div className="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm text-center">
                    <div className="w-12 h-12 bg-slate-100 text-slate-600 rounded-full mx-auto flex items-center justify-center mb-3">
                        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p className="text-3xl font-extrabold text-slate-800">{stats.total_logs}</p>
                    <p className="text-sm text-slate-500 font-medium">Log Aktivitas</p>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                
                {/* Left Column: System Health & Shortcuts */}
                <div className="lg:col-span-1 space-y-6">
                    <div className="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                        <h3 className="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <svg className="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            Kesehatan Sistem
                        </h3>
                        <ul className="space-y-3">
                            <li className="flex justify-between items-center text-sm">
                                <span className="text-slate-500">Status Server</span>
                                <span className="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-md font-bold text-xs">Online</span>
                            </li>
                            <li className="flex justify-between items-center text-sm">
                                <span className="text-slate-500">Versi PHP</span>
                                <span className="font-semibold text-slate-700">{sysInfo?.php_version || '8.x'}</span>
                            </li>
                            <li className="flex justify-between items-center text-sm">
                                <span className="text-slate-500">Versi Framework</span>
                                <span className="font-semibold text-slate-700">Laravel {sysInfo?.laravel_version || '10.x'}</span>
                            </li>
                        </ul>
                    </div>

                    <div className="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                        <h3 className="text-lg font-bold text-slate-800 mb-4">Master Data</h3>
                        <div className="space-y-2">
                            <a href="/users" className="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                                <div className="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <span className="font-semibold text-slate-700 text-sm">Manajemen Pengguna</span>
                            </a>
                            <a href="/satkers" className="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                                <div className="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <span className="font-semibold text-slate-700 text-sm">Daftar Satuan Kerja</span>
                            </a>
                            <a href="/paket-pekerjaans" className="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                                <div className="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                </div>
                                <span className="font-semibold text-slate-700 text-sm">Master Paket Pekerjaan</span>
                            </a>
                        </div>
                    </div>
                </div>

                {/* Right Column: Recent Activity Logs */}
                <div className="lg:col-span-2">
                    <div className="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm h-full">
                        <div className="flex items-center justify-between mb-6">
                            <h3 className="text-lg font-bold text-slate-800">Aktivitas Terbaru Sistem</h3>
                            <a href="/activity-log" className="text-sm font-semibold text-blue-600 hover:text-blue-800">Lihat Semua</a>
                        </div>
                        
                        <div className="space-y-4">
                            {recentLogs && recentLogs.length > 0 ? (
                                recentLogs.map((log, index) => (
                                    <div key={index} className="flex gap-4 p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                                        <div className="flex-shrink-0 mt-1">
                                            <div className="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                                                {log.causer?.name?.charAt(0) || '?'}
                                            </div>
                                        </div>
                                        <div>
                                            <p className="text-sm text-slate-800">
                                                <span className="font-bold">{log.causer?.name || 'Sistem'}</span> {log.description} pada <span className="font-semibold">{log.log_name}</span>.
                                            </p>
                                            <p className="text-xs text-slate-500 mt-1">
                                                {new Date(log.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' })}
                                            </p>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <div className="text-center py-10">
                                    <svg className="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p className="text-slate-500 font-medium">Belum ada log aktivitas tercatat.</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    );

    return (
        <div className="font-sans text-slate-800 pb-12">
            {user.role === 'karyawan' && renderKaryawanView()}
            {user.role === 'atasan' && renderAtasanView()}
            {user.role === 'admin' && renderAdminView()}
        </div>
    );
};

const rootElement = document.getElementById('react-dashboard-root');
if (rootElement) {
    const root = createRoot(rootElement);
    
    const user = {
        name: rootElement.getAttribute('data-user-name'),
        role: rootElement.getAttribute('data-user-role')
    };

    let stats = {};
    let recentLogs = [];
    let sysInfo = {};

    try {
        stats = JSON.parse(rootElement.getAttribute('data-stats') || '{}');
        recentLogs = JSON.parse(rootElement.getAttribute('data-recent-logs') || '[]');
        sysInfo = JSON.parse(rootElement.getAttribute('data-sys-info') || '{}');
    } catch(e) {
        console.error(e);
    }
    
    root.render(<Dashboard user={user} stats={stats} recentLogs={recentLogs} sysInfo={sysInfo} />);
}
