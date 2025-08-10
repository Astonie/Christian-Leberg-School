import { ReactNode } from "react";
import { LayoutDashboard, Users, HandCoins, Wallet, Receipt, BarChart3, Settings, FileStack } from "lucide-react";

export type NavChild = { label: string; href: string };
export type NavItem = {
  label: string;
  href?: string;
  icon?: ReactNode;
  children?: NavChild[];
};

export const navItems: NavItem[] = [
  { label: "Dashboard", href: "/dashboard", icon: <LayoutDashboard className="h-4 w-4" /> },
  {
    label: "Clients",
    icon: <Users className="h-4 w-4" />,
    children: [
      { label: "All Clients", href: "/clients" },
      { label: "Add Client", href: "/clients/new" },
    ],
  },
  {
    label: "Loan Products",
    icon: <FileStack className="h-4 w-4" />,
    children: [
      { label: "Products", href: "/loan-products" },
      { label: "Create Product", href: "/loan-products/new" },
    ],
  },
  {
    label: "Applications",
    icon: <HandCoins className="h-4 w-4" />,
    children: [
      { label: "Applications", href: "/applications" },
    ],
  },
  {
    label: "Loans",
    icon: <Wallet className="h-4 w-4" />,
    children: [
      { label: "Active Loans", href: "/loans" },
    ],
  },
  { label: "Repayments", href: "/repayments", icon: <Receipt className="h-4 w-4" /> },
  { label: "Disbursements", href: "/disbursements", icon: <HandCoins className="h-4 w-4" /> },
  { label: "Reports", href: "/reports", icon: <BarChart3 className="h-4 w-4" /> },
  {
    label: "Admin",
    icon: <Settings className="h-4 w-4" />,
    children: [
      { label: "Users", href: "/admin/users" },
      { label: "Roles & Permissions", href: "/admin/roles" },
      { label: "System Settings", href: "/admin/settings" },
    ],
  },
];


