export type Role = { id: string; name: string; permissions: string[] };
export type User = { id: string; name: string; email: string; roleId: string };

const ROLES: Role[] = [
  { id: "r-admin", name: "Admin", permissions: ["*:"] },
  { id: "r-officer", name: "Loan Officer", permissions: ["clients:read","loans:read","applications:write"] },
];
const USERS: User[] = [
  { id: "u-1", name: "Admin", email: "admin@uplifta.test", roleId: "r-admin" },
  { id: "u-2", name: "Officer", email: "officer@uplifta.test", roleId: "r-officer" },
];

export async function listRoles(): Promise<Role[]> { await new Promise(r=>setTimeout(r,300)); return ROLES; }
export async function listUsers(): Promise<User[]> { await new Promise(r=>setTimeout(r,300)); return USERS; }


