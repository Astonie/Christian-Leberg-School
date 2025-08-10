"use client";

import * as SelectPrimitive from "@radix-ui/react-dropdown-menu";
import { cn } from "@/lib/utils";

export function Select({ children, label }: { children: React.ReactNode; label?: string }) {
  return (
    <SelectPrimitive.Root>
      <SelectPrimitive.Trigger className="inline-flex items-center justify-between rounded-md border bg-background px-3 py-2 text-sm">
        {label}
      </SelectPrimitive.Trigger>
      <SelectPrimitive.Content className="z-50 min-w-[12rem] rounded-md border bg-popover p-1 text-popover-foreground shadow-md">
        {children}
      </SelectPrimitive.Content>
    </SelectPrimitive.Root>
  );
}
export function SelectItem({ children, onSelect }: { children: React.ReactNode; onSelect?: () => void }) {
  return (
    <SelectPrimitive.Item className={cn("relative flex cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none hover:bg-accent hover:text-accent-foreground")}
      onSelect={onSelect}
    >
      {children}
    </SelectPrimitive.Item>
  );
}


