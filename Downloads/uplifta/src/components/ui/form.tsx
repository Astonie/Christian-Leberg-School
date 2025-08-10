"use client";

import * as React from "react";
import * as LabelPrimitive from "@radix-ui/react-label";

export function FormField({
  label,
  children,
  error,
  description,
}: {
  label: string;
  children: React.ReactNode;
  error?: string;
  description?: string;
}) {
  const id = React.useId();
  return (
    <div className="space-y-2">
      <LabelPrimitive.Root htmlFor={id} className="text-sm font-medium">
        {label}
      </LabelPrimitive.Root>
      {React.isValidElement(children)
        ? (React.cloneElement(children as React.ReactElement, { id }))
        : (children)}
      {description && (
        <p className="text-xs text-muted-foreground">{description}</p>
      )}
      {error && <p className="text-xs text-destructive">{error}</p>}
    </div>
  );
}


