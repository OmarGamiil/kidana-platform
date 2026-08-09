export function formatRelativeTime(isoDate: string, locale: string): string {
    const rtf = new Intl.RelativeTimeFormat(locale, { numeric: 'auto' });
    const diffMs = new Date(isoDate).getTime() - Date.now();
    const diffMinutes = Math.round(diffMs / 60000);

    if (Math.abs(diffMinutes) < 60) {
        return rtf.format(diffMinutes, 'minute');
    }

    const diffHours = Math.round(diffMinutes / 60);

    if (Math.abs(diffHours) < 24) {
        return rtf.format(diffHours, 'hour');
    }

    const diffDays = Math.round(diffHours / 24);

    return rtf.format(diffDays, 'day');
}
