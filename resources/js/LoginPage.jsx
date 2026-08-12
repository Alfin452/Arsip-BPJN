import React from 'react';
import { createRoot } from 'react-dom/client';

const LoginPage = ({ csrfToken, oldEmail, errors, status }) => {
    return (
        <div className="min-h-screen flex bg-slate-50 font-sans text-slate-800">
            {/* Left Side - Visual/Branding (Hidden on mobile) */}
            <div className="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-blue-900 flex-col justify-between p-12">
                <div className="absolute inset-0 opacity-20 mix-blend-overlay bg-[url('https://images.unsplash.com/photo-1541888081622-c943144e7314?q=80&w=2000&auto=format&fit=crop')] bg-cover bg-center"></div>
                <div className="absolute inset-0 bg-gradient-to-br from-blue-900/90 via-blue-950/80 to-slate-900/90"></div>
                
                <div className="relative z-10">
                    <a href="/" className="inline-flex items-center gap-3 bg-white/10 px-4 py-2 rounded-full backdrop-blur-sm border border-white/20 text-white hover:bg-white/20 transition">
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Beranda
                    </a>
                </div>

                <div className="relative z-10 text-white max-w-lg mt-auto mb-10">
                    <div className="flex items-center gap-4 mb-8">
                        <div className="w-16 h-16 bg-white rounded-2xl flex items-center justify-center p-2 shadow-xl">
                            <img src="/logo/Logo_Kementerian_Pekerjaan_Umum_Republik_Indonesia.svg" alt="Logo" className="w-full h-full object-contain" />
                        </div>
                        <div>
                            <h2 className="text-2xl font-bold tracking-tight">Kementerian PUPR</h2>
                            <p className="text-blue-300 text-sm font-medium">Balai Pelaksanaan Jalan Nasional</p>
                        </div>
                    </div>
                    
                    <h1 className="text-4xl lg:text-5xl font-extrabold mb-6 leading-tight text-transparent bg-clip-text bg-gradient-to-r from-white to-blue-200">
                        Portal Layanan <br/>Sistem Informasi BPJN
                    </h1>
                    <p className="text-blue-100 text-lg leading-relaxed opacity-90">
                        Platform digital terintegrasi untuk pengelolaan arsip dan pemantauan kinerja pelaksanaan jalan nasional secara transparan dan akuntabel.
                    </p>
                </div>
                
                <div className="relative z-10 flex gap-2">
                    <div className="w-8 h-1.5 bg-yellow-400 rounded-full"></div>
                    <div className="w-2 h-1.5 bg-white/30 rounded-full"></div>
                    <div className="w-2 h-1.5 bg-white/30 rounded-full"></div>
                </div>
            </div>

            {/* Right Side - Login Form */}
            <div className="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-24 bg-white relative">
                {/* Mobile back button */}
                <div className="absolute top-6 left-6 lg:hidden">
                    <a href="/" className="inline-flex items-center gap-2 text-slate-500 hover:text-slate-800 transition">
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Beranda
                    </a>
                </div>

                <div className="w-full max-w-md">
                    <div className="text-center lg:text-left mb-10">
                        <h2 className="text-3xl font-extrabold text-slate-900 mb-2">Selamat Datang</h2>
                        <p className="text-slate-500">Silakan masukkan kredensial Anda untuk melanjutkan ke dashboard sistem.</p>
                    </div>

                    {status && (
                        <div className="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium flex items-center gap-3">
                            <svg className="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {status}
                        </div>
                    )}

                    <form method="POST" action="/login" className="space-y-6">
                        <input type="hidden" name="_token" value={csrfToken} />

                        {/* Email Input */}
                        <div>
                            <label htmlFor="email" className="block text-sm font-semibold text-slate-700 mb-2">Alamat Email</label>
                            <div className="relative">
                                <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                </div>
                                <input 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    defaultValue={oldEmail}
                                    required 
                                    autoFocus 
                                    autoComplete="username"
                                    className={`pl-11 w-full bg-slate-50 border ${errors.email ? 'border-rose-300 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-200 focus:ring-blue-600 focus:border-blue-600'} text-slate-900 rounded-xl shadow-sm py-3 transition-colors`}
                                    placeholder="nama@email.com"
                                />
                            </div>
                            {errors.email && (
                                <p className="mt-2 text-sm text-rose-600 flex items-center gap-1.5">
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {errors.email[0]}
                                </p>
                            )}
                        </div>

                        {/* Password Input */}
                        <div>
                            <label htmlFor="password" className="block text-sm font-semibold text-slate-700 mb-2">Kata Sandi</label>
                            <div className="relative">
                                <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <input 
                                    id="password" 
                                    type="password" 
                                    name="password" 
                                    required 
                                    autoComplete="current-password"
                                    className={`pl-11 w-full bg-slate-50 border ${errors.password ? 'border-rose-300 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-200 focus:ring-blue-600 focus:border-blue-600'} text-slate-900 rounded-xl shadow-sm py-3 transition-colors`}
                                    placeholder="••••••••"
                                />
                            </div>
                            {errors.password && (
                                <p className="mt-2 text-sm text-rose-600 flex items-center gap-1.5">
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {errors.password[0]}
                                </p>
                            )}
                        </div>

                        {/* Remember Me */}
                        <div className="flex items-center justify-between">
                            <label htmlFor="remember_me" className="inline-flex items-center">
                                <input id="remember_me" type="checkbox" className="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 w-4 h-4" name="remember" />
                                <span className="ml-2 text-sm text-slate-600 font-medium">Ingat Saya</span>
                            </label>
                            
                            <a href="/forgot-password" className="text-sm font-semibold text-blue-600 hover:text-blue-800 transition">
                                Lupa Kata Sandi?
                            </a>
                        </div>

                        {/* Submit Button */}
                        <div className="pt-2">
                            <button type="submit" className="w-full flex items-center justify-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all hover:shadow-lg hover:-translate-y-0.5">
                                Masuk Aplikasi
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            </button>
                        </div>
                    </form>
                    
                    <div className="mt-10 text-center text-sm text-slate-500">
                        &copy; {new Date().getFullYear()} BPJN - Kementerian PUPR.
                    </div>
                </div>
            </div>
        </div>
    );
};

// Mount component
const rootElement = document.getElementById('react-login-root');
if (rootElement) {
    const root = createRoot(rootElement);
    
    // Parse props from data attributes
    const csrfToken = rootElement.getAttribute('data-csrf');
    const oldEmail = rootElement.getAttribute('data-old-email') || '';
    const status = rootElement.getAttribute('data-status') || '';
    let errors = {};
    try {
        errors = JSON.parse(rootElement.getAttribute('data-errors') || '{}');
    } catch (e) {
        console.error("Failed to parse errors", e);
    }
    
    root.render(<LoginPage csrfToken={csrfToken} oldEmail={oldEmail} errors={errors} status={status} />);
}
