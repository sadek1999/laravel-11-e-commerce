import { TProduct } from "@/types";
import CurrencyFormatter from "../Core/CurrencyFormatter";

const ProductItem = ({ product }: { product: TProduct }) => {
  return (
    <div>
      <div className="card bg-base-100 w-96 shadow-xl">
        <figure>
          <img src={product.image} alt="Shoes" />
        </figure>
        <div className="card-body">
          <h2 className="card-title">{product.title}</h2>
          <p>If a dog chews shoes whose shoes does he choose?</p>
           <p><CurrencyFormatter amount={product.price}></CurrencyFormatter></p>
          <div className="card-actions justify-end">
            <button className="btn btn-primary">Buy Now</button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductItem;
