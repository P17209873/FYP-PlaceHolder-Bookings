/**
 * XSS Prevention input sanitization as discussed in Rule 1 on below page:
 * {@link https://owasp.org/www-project-cheat-sheets/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html}
 *
 * Function uses a regular expression to find and replace any characters that are illegal
 *
 * Credit for this function goes to:
 * {@link https://stackoverflow.com/users/1266725/silentimp}
 *
 * @param string
 * @returns {void | *}
 */
export default function sanitize(string) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#x27;',
        "/": '&#x2F;',
        "`": '&grave',
        " ": '&nbsp;',
    };
    const reg = /[&<>"'/]/ig;
    return string.replace(reg, (match)=>(map[match]));
}