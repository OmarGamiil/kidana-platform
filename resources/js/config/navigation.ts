import {
    AlertTriangle,
    ClipboardList,
    FileText,
    FolderOpen,
    LayoutGrid,
    MapPin,
    Users,
    Wrench,
} from '@lucide/vue';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

/**
 * The Hajj Dashboard's Figma screen shows all 8 of these nav items — they
 * stay visible to match the real design, but only Overview has a route
 * built this phase. The rest render disabled (not a fake route to an empty
 * stub page) until their screens are implemented in a later phase.
 */
export const officeNavItems: NavItem[] = [
    { title: 'نظرة عامة', href: dashboard(), icon: LayoutGrid },
    {
        title: 'خطة أعمال الموسم',
        href: '#',
        icon: ClipboardList,
        enabled: false,
    },
    { title: 'الخدمات', href: '#', icon: Wrench, enabled: false },
    { title: 'الزيارات الميدانية', href: '#', icon: MapPin, enabled: false },
    {
        title: 'الملاحظات والمعالجة',
        href: '#',
        icon: AlertTriangle,
        enabled: false,
    },
    { title: 'التقارير', href: '#', icon: FileText, enabled: false },
    { title: 'المستندات', href: '#', icon: FolderOpen, enabled: false },
    { title: 'أعضاء المكتب', href: '#', icon: Users, enabled: false },
];
