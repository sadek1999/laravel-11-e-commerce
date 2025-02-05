import { TProduct } from "@/types";
import CurrencyFormatter from "../Core/CurrencyFormatter";
import { Link } from "@inertiajs/react";

const ProductItem = ({ product }: { product: TProduct }) => {
  return (
    <div>
      <div className="card bg-base-100 w-96 shadow-xl">
        <Link href={route('product.show',product.slug)}>

        <figure>
          <img src='https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' alt="Shoes" />
        </figure>
        </Link>
        <div className="card-body">
          <h2 className="card-title">{product.title}</h2>
          <p>If a dog chews shoes whose shoes does he choose?</p>
           <p><CurrencyFormatter amount={product.price}></CurrencyFormatter></p>
          <div className="card-actions justify-end">
            <button className="btn btn-primary">Add Card</button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductItem;
