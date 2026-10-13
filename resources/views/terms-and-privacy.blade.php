@extends('layouts.app')

@section('title', 'Terms & Privacy - Kizoku')

@section('content')
<section class="px-6 pt-32 pb-20">
    <div class="mx-auto max-w-4xl">
        <h1 class="mb-12 font-saa-series-e-dot text-3xl 2xl:text-4xl uppercase tracking-widest">Privacy Policy</h1>

        <div class="space-y-10 text-gray-700 text-sm lg:text-base leading-relaxed">
            <p>
                KIZOKU values your privacy and is committed to safeguarding your personal data. This Privacy Policy
                explains how we collect, use, disclose, and protect your personal information, as well as your
                rights under applicable Personal Data Protection laws.
            </p>

            <div>
                <h2 class="mb-3 font-medium text-lg uppercase tracking-widest">Collection of Personal Data</h2>
                <p class="mb-3">We collect personal information that you provide directly to us, including when you:</p>
                <ul class="space-y-1 list-disc list-inside">
                    <li>Register for an account</li>
                    <li>Provide your phone number</li>
                    <li>Provide your email address</li>
                    <li>Log in via Facebook or Google</li>
                </ul>
            </div>

            <div>
                <h2 class="mb-3 font-medium text-lg uppercase tracking-widest">Types of Data We Collect</h2>
                <p class="mb-3">We may collect the following categories of personal data:</p>
                <ul class="space-y-1 list-disc list-inside">
                    <li><strong>Personal details:</strong> such as name, surname, gender, date of birth</li>
                    <li><strong>Contact information:</strong> including address, phone number, and email</li>
                    <li><strong>Account information:</strong> such as username, password, and transaction history</li>
                    <li><strong>Financial and transaction data:</strong> such as purchase history, payment details, and bank account information</li>
                    <li><strong>Technical data:</strong> including IP address, cookie identifiers, and activity logs</li>
                    <li><strong>Other data:</strong> such as photos, videos, or any information considered personal data under applicable laws</li>
                </ul>
            </div>

            <div>
                <h2 class="mb-3 font-medium text-lg uppercase tracking-widest">Children's Privacy</h2>
                <p>
                    If you are under 20 years of age or legally restricted, we may collect and process your personal
                    data only with consent from a parent or legal guardian, or as permitted by law. If we discover
                    that we have collected a child's data without proper consent, we will take steps to delete it.
                </p>
            </div>

            <div>
                <h2 class="mb-3 font-medium text-lg uppercase tracking-widest">Use of Personal Data</h2>
                <p class="mb-3">We use your personal data to:</p>
                <ul class="space-y-1 list-disc list-inside">
                    <li>Create and manage user accounts</li>
                    <li>Deliver products and services</li>
                    <li>Improve our offerings and user experience</li>
                    <li>Manage internal operations</li>
                    <li>Conduct marketing and promotional activities</li>
                    <li>Provide customer support and after-sales services</li>
                    <li>Collect feedback</li>
                    <li>Process payments</li>
                    <li>Enforce our Terms and Conditions</li>
                    <li>Comply with legal and regulatory obligations</li>
                </ul>
            </div>

            <div>
                <h2 class="mb-3 font-medium text-lg uppercase tracking-widest">Disclosure of Personal Data</h2>
                <p class="mb-3">We may share your personal data in the following cases:</p>
                <ul class="space-y-1 list-disc list-inside">
                    <li><strong>Within our organization:</strong> to support and improve our services</li>
                    <li><strong>Service providers:</strong> such as payment processors and developers (subject to their own privacy policies)</li>
                    <li><strong>Business partners:</strong> to coordinate services and provide relevant product information</li>
                </ul>
            </div>

            <div>
                <h2 class="mb-3 font-medium text-lg uppercase tracking-widest">Data Retention</h2>
                <p>
                    We retain your personal data for as long as necessary to fulfill the purposes outlined in this
                    policy, or as required by law. Once no longer needed, your data will be deleted, destroyed, or
                    anonymized.
                </p>
            </div>

            <div>
                <h2 class="mb-3 font-medium text-lg uppercase tracking-widest">Your Rights</h2>
                <p class="mb-3">Under applicable laws, you have the right to:</p>
                <ul class="space-y-1 list-disc list-inside">
                    <li>Withdraw your consent at any time</li>
                    <li>Access your personal data and request copies</li>
                    <li>Request data portability in a machine-readable format</li>
                    <li>Object to the processing of your data</li>
                    <li>Request deletion, destruction, or anonymization</li>
                    <li>Request restriction of processing</li>
                    <li>Correct or update inaccurate data</li>
                    <li>File a complaint with relevant authorities</li>
                </ul>
                <p class="mt-3">
                    To exercise your rights, please contact us (email customer support). We will respond within 30
                    days. If your request is denied, we will explain the reason via appropriate communication
                    channels.
                </p>
            </div>

            <div>
                <h2 class="mb-3 font-medium text-lg uppercase tracking-widest">Advertising and Marketing</h2>
                <p>
                    We may use your data to personalize content, advertisements, and marketing campaigns through
                    tools such as Google, Facebook, and tracking technologies.
                </p>
            </div>

            <div>
                <h2 class="mb-3 font-medium text-lg uppercase tracking-widest">Cookies</h2>
                <p>
                    We use cookies and similar technologies to enhance your browsing experience, personalize
                    content, and analyze website usage. You can disable cookies through your browser settings if
                    you prefer.
                </p>
            </div>

            <div>
                <h2 class="mb-3 font-medium text-lg uppercase tracking-widest">Data Security</h2>
                <p>
                    We implement appropriate security measures to protect your personal data from unauthorized
                    access, loss, misuse, or disclosure. These measures include administrative, technical, and
                    physical safeguards.
                </p>
            </div>

            <div>
                <h2 class="mb-3 font-medium text-lg uppercase tracking-widest">Data Breach Notification</h2>
                <p>
                    In the event of a data breach, we will notify the relevant authorities promptly (within 72 hours
                    where feasible), unless the breach poses no significant risk. If there is a high risk to your
                    rights, we will inform you directly and outline corrective actions.
                </p>
            </div>

            <div>
                <h2 class="mb-3 font-medium text-lg uppercase tracking-widest">Changes to This Policy</h2>
                <p>
                    We may update this Privacy Policy from time to time. Please review it periodically on our
                    website for any changes.
                </p>
            </div>

            <p class="text-gray-400 text-xs">Last updated: [DATE]</p>
        </div>
    </div>
</section>
@endsection
